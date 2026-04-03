<?php

namespace App\Console\Commands;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class GenerateAdminRoutesJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:admin-routes-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect all registered GET routes under /admin (excluding AJAX) and output as JSON with Blade view paths and keywords';

    /**response
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $adminRoutes = $this->getAdminRoutes();
        $items = $this->processRoutes($adminRoutes);
        $items = array_merge($this->additionalItemsWithoutKeywords(), $items);
        $this->generateAndSaveJsonFiles($items);
    }



    private function getAdminRoutes(): Collection
    {
        $routes = Route::getRoutes();
        return collect($routes->getRoutesByMethod()['GET'] ?? [])
            ->filter(function ($route) {
                return Str::startsWith($route->uri(), 'admin')
                    && !Str::startsWith($route->uri(), 'admin/component')
                    && !Str::startsWith($route->uri(), 'admin/ajax')
                    && !Str::contains($route->getActionName(), 'Ajax')
                    && !collect($route->middleware())->contains('api')
                    && !in_array($route->uri(), $this->avoidRoutes());
            });
    }

    private function avoidRoutes(): array
    {
        return [
            'admin/blog/edit',
            'admin/vat-tax',
            'admin/report/get-tax-details'
        ];
    }


    private function processRoutes($adminRoutes)
    {
        return $adminRoutes->map(function ($route) {
            $viewPath = $this->getBladePathFromController($route);

            if (!$viewPath) {
                return null;
            }
            // skip dynamic route parameters
            if (preg_match('/{[^}]+}/', $route->uri())) {
                return '';
            }
            $fullPath  = $this->getFullViewPath($viewPath);
            $keywords  = File::exists($fullPath) ? $this->extractKeywordsFromView($fullPath) : '';
            $pageTitle = File::exists($fullPath) ? $this->extractPageTitleFromView($fullPath) : $this->getRouteName($route->getName());
            return [
                'page_title' => $pageTitle,
                'page_title_value' => $pageTitle,
                'key' => base64_encode($route->uri()),
                'uri' => $route->uri(),
                'uri_count' => count(explode('/', $route->uri())),
                'method' => in_array('GET', $route->methods()) ? 'GET' : $route->methods()[0],
                'view' => $viewPath,
                'keywords' => $keywords,
                "priority" => 2,
                "type" => 'page'
            ];
        })
            ->filter()
            ->unique('uri')
            ->values()
            ->all();
    }

    private function getRouteName($actualRouteName): string
    {
        $routeNameParts = explode('.', $actualRouteName);
        if (count($routeNameParts) >= 2) {
            $lastPart = $routeNameParts[count($routeNameParts) - 1];
            $secondLastPart = $routeNameParts[count($routeNameParts) - 2];

            if (strtolower($lastPart) === 'index') {
                $lastPart = 'List';
            }

            $lastPartWords = explode(' ', str_replace(['_', '-'], ' ', $lastPart));
            $secondLastPartWords = explode(' ', str_replace(['_', '-'], ' ', $secondLastPart));
            $allWords = array_merge($secondLastPartWords, $lastPartWords);
            $uniqueWords = [];

            foreach ($allWords as $word) {
                $lowerWord = strtolower($word);
                if (empty($uniqueWords) || strtolower(end($uniqueWords)) !== $lowerWord) {
                    $uniqueWords[] = $word;
                }
            }

            if (count($uniqueWords) > 1 && strtolower($uniqueWords[0]) === strtolower(end($uniqueWords))) {
                array_shift($uniqueWords);
            }

            $uniqueWords = array_filter($uniqueWords, function ($word) {
                return strtolower($word) !== 'rental';
            });

            $routeName = ucwords(implode(' ', $uniqueWords));
        } else {
            $routeName = ucwords(str_replace(['.', '_', '-'], ' ', Str::afterLast($actualRouteName, '.')));
        }
        return $routeName;
    }

    private function generateAndSaveJsonFiles($items): void
    {

        $filteredItems = collect($items)->filter(function ($item) {
            return !empty($item['page_title']);
        });
        $itemsWithoutKeywords = $filteredItems->map(function ($item) {
            return collect($item)->except('keywords')->toArray();
        })->values()->all();

        $langItems = $filteredItems->map(function ($item) {
            $keywords = array_map('trim', explode(',', $item['keywords']));
            $keywordMap = [];

            foreach ($keywords as $keyword) {
                $processedKey = ucfirst(str_replace('_', ' ', removeSpecialCharacters($keyword)));
                $keywordMap[$keyword] = $processedKey;
            }

            return [
                'key'              => $item['key'],
                'page_title'       => $item['page_title'],
                'page_title_value' => $item['page_title'],
                'keywords'         => $keywordMap,
            ];
        })->values()->all();


        $json = json_encode($itemsWithoutKeywords, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $langJson = json_encode($langItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $path = public_path('json/admin/admin_formatted_routes.json');
        $langPath = public_path('json/admin/lang/en.json');

        $this->isDirectoryExists(dirname($path));
        $this->isDirectoryExists(dirname($langPath));

        file_put_contents($path, $json);
        file_put_contents($langPath, $langJson);

        $this->info("Wrote " . count($itemsWithoutKeywords) . " URIs to {$path}");
        $this->info("Wrote " . count($langItems) . " URIs to {$langPath}");
    }


    private function isDirectoryExists($dir): void
    {
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
    }


    private function getBladePathFromController($route): array|string|null
    {
        $action = $route->getAction();
        $controller = $action['controller'] ?? null;

        if ($controller) {
            return $this->extractViewPathFromControllerMethod($controller);
        } elseif ($route->getAction()['uses'] instanceof \Closure) {
            return $this->extractViewPathFromClosure($route->getAction()['uses']);
        }

        return null;
    }


    private function extractViewPathFromControllerMethod($controllerWithMethod): array|string|null
    {
        list($controllerClass, $method) = explode('@', $controllerWithMethod);

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
            return null;
        }

        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine();
        $endLine = $reflectionMethod->getEndLine();
        if (!$this->controllerReturnsView($filename, $startLine, $endLine)) {
            return null;
        }

        return $this->extractViewPathFromCode($filename, $startLine, $endLine);
    }


    private function extractViewPathFromClosure(Closure $closure): array|string|null
    {
        $reflectionFunction = new \ReflectionFunction($closure);
        $filename = $reflectionFunction->getFileName();
        $startLine = $reflectionFunction->getStartLine();
        $endLine = $reflectionFunction->getEndLine();

        return $this->extractViewPathFromCode($filename, $startLine, $endLine);
    }

    private function extractViewPathFromCode($filename, $startLine, $endLine): array|string|null
    {
        $file = file($filename);
        $codeBody = implode('', array_slice($file, $startLine - 1, $endLine - $startLine + 1));

        if (preg_match("/view\\(['\"](.*?)['\"]/", $codeBody, $matches)) {
            $bladePath = $matches[1];
            return str_replace('.', '/', $bladePath);
        }

        return null;
    }


    public function extractIncludedViews(string $viewPath): array
    {
        if (!File::exists($viewPath)) {
            return [];
        }

        $source = File::get($viewPath);

        preg_match_all(
            '/@include\(\s*[\'"]([^\'"]+)[\'"]\s*(?:,.*?)?\)/',
            $source,
            $matches
        );

        return $matches[1] ?? [];
    }


    private function getFullViewPath(string $viewPath): string
    {

        if (File::exists($viewPath)) {
            return $viewPath;
        }

        if (strpos($viewPath, '::') !== false) {
            [$moduleName, $viewName] = explode('::', $viewPath, 2);
            $properModuleName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $moduleName)));
            $viewFilePath = str_replace('.', '/', $viewName);

            $modulePaths = [
                base_path('Modules/' . $properModuleName . '/Resources/views/' . $viewFilePath . '.blade.php'),
                base_path('Modules/' . ucfirst($moduleName) . '/Resources/views/' . $viewFilePath . '.blade.php'),
                base_path('Modules/' . $moduleName . '/Resources/views/' . $viewFilePath . '.blade.php'),
            ];

            foreach ($modulePaths as $path) {
                if (File::exists($path)) {
                    return $path;
                }
            }

            if (function_exists('module_path')) {
                try {
                    $moduleViewPath = module_path($properModuleName, 'Resources/views/' . $viewFilePath . '.blade.php');
                    if (File::exists($moduleViewPath)) {
                        return $moduleViewPath;
                    }
                } catch (\Exception $e) {
                    // Continue to next attempt
                }
            }
        }

        // Standard Laravel view path
        $viewFilePath = str_replace('.', '/', $viewPath);
        $fullPath = resource_path('views/' . $viewFilePath . '.blade.php');
        if (File::exists($fullPath)) {
            return $fullPath;
        }

        // PHP view file
        $phpPath = resource_path('views/' . $viewFilePath . '.php');
        if (File::exists($phpPath)) {
            return $phpPath;
        }

        // Index file in directory
        $dirPath = resource_path('views/' . $viewFilePath);
        if (File::exists($dirPath . '/index.blade.php')) {
            return $dirPath . '/index.blade.php';
        }

        // Module views without :: notation
        $modulesBasePath = base_path('Modules');
        if (File::exists($modulesBasePath)) {
            $modules = File::directories($modulesBasePath);

            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $moduleViewsPath = $modulePath . '/Resources/views';

                if (File::exists($moduleViewsPath)) {
                    $moduleViewPath = $moduleViewsPath . '/' . $viewFilePath . '.blade.php';
                    if (File::exists($moduleViewPath)) {
                        return $moduleViewPath;
                    }

                    $modulePhpPath = $moduleViewsPath . '/' . $viewFilePath . '.php';
                    if (File::exists($modulePhpPath)) {
                        return $modulePhpPath;
                    }

                    $moduleDirPath = $moduleViewsPath . '/' . $viewFilePath;
                    if (File::exists($moduleDirPath . '/index.blade.php')) {
                        return $moduleDirPath . '/index.blade.php';
                    }
                }

                // Alternative module views path
                $altModuleViewsPath = $modulePath . '/views';
                if (File::exists($altModuleViewsPath)) {
                    $altModuleViewPath = $altModuleViewsPath . '/' . $viewFilePath . '.blade.php';
                    if (File::exists($altModuleViewPath)) {
                        return $altModuleViewPath;
                    }
                }
            }
        }

        // Return the constructed path even if not found (for debugging)
        return $fullPath;
    }

    private function extractTextContent(string $viewPath): string
    {
        if (!File::exists($viewPath)) {
            return '';
        }

        $content = File::get($viewPath);
        $extractedContent = [];


        $this->extractContentFromHtmlTags($content, $extractedContent);
        $this->extractContentFromIncludes($content, $viewPath, $extractedContent);
        $this->extractTitlesAndArrayData($content, $extractedContent);
        $this->extractTranslations($content, $extractedContent);

        $combinedContent = implode(' ', $extractedContent);

        // Clean up the content
        return trim(preg_replace('/\s+/', ' ', $combinedContent));
    }


    private function extractContentFromHtmlTags(string $content, array &$extractedContent): void
    {
        $content = preg_replace('/<code\b[^>]*>.*?<\/code>/is', '', $content);
        preg_match_all('/<(h[1-6]|p|span|div|li|a|button|label|strong|b|i|em)[^>]*>(.*?)<\/\1>/is', $content, $matches);

        foreach ($matches[2] as $tagContent) {
            // Extract translate function content
            preg_match_all('/translate\([\'"]([^\'"]*?)[\'"]\)/', $tagContent, $translateMatches);
            foreach ($translateMatches[1] as $translatedText) {
                $extractedContent[] = $translatedText;
            }

            // Extract blade expressions {{ }}
            preg_match_all('/\{\{\s*(.*?)\s*\}\}/', $tagContent, $bladeMatches);
            foreach ($bladeMatches[1] as $bladeExpression) {
                //  blade expression contains translate
                if (preg_match('/translate\([\'"]([^\'"]*?)[\'"]\)/', $bladeExpression, $translateMatch)) {
                    $extractedContent[] = $translateMatch[1];
                } //  for string concatenation in blade expressions
                elseif (strpos($bladeExpression, 'translate') !== false) {
                    preg_match_all('/translate\([\'"]([^\'"]*?)[\'"]\)/', $bladeExpression, $innerTranslateMatches);
                    foreach ($innerTranslateMatches[1] as $translatedText) {
                        $extractedContent[] = $translatedText;
                    }
                }
            }

            // Strip HTML and blade syntax to get raw text
            $plainText = strip_tags($tagContent);
            $plainText = preg_replace([
                '/\{\{.*?\}\}/',
                '/@[\w]+\s*(\([^\)]*\))?/',
            ], ' ', $plainText);

            if (!empty(trim($plainText))) {
                $extractedContent[] = $plainText;
            }
        }
    }


    private function extractContentFromIncludes(string $content, string $viewPath, array &$extractedContent): void
    {
        preg_match_all('/@include\([\'"]([^\'"]+)[\'"](.*?)\)/', $content, $includeMatches);

        foreach ($includeMatches[1] as $includePath) {
            $includePath = str_replace('.', '/', $includePath);
            $fullIncludePath = resource_path('views/' . $includePath . '.blade.php');

            if (File::exists($fullIncludePath)) {
                $processedViews = [$viewPath];
                $includeContent = $this->extractTextContentFromInclude($fullIncludePath, $processedViews);
                $extractedContent[] = $includeContent;
            }
        }
    }


    private function extractTitlesAndArrayData(string $content, array &$extractedContent): void
    {
        preg_match_all('/[\'"]title[\'"]\s*=>\s*[\'"]([^\'"]*?)[\'"]/i', $content, $titleMatches);
        foreach ($titleMatches[1] as $title) {
            $extractedContent[] = $title;
        }
    }

    private function extractTranslations(string $content, array &$extractedContent): void
    {
        preg_match_all('/translate\([\'"]([^\'"]*?)[\'"]\)/', $content, $allTranslateMatches);
        foreach ($allTranslateMatches[1] as $translatedText) {
            $extractedContent[] = $translatedText;
        }
    }

    private function extractTextContentFromInclude(string $viewPath, array &$processedViews): string
    {
        if (in_array($viewPath, $processedViews)) {
            return ''; // Prevent infinite recursion
        }
        $processedViews[] = $viewPath;
        if (!File::exists($viewPath)) {
            return '';
        }
        return $this->extractTextContent($viewPath);
    }

    private function extractPageTitleFromView(string $viewPath): string
    {
        if (!File::exists($viewPath)) {
            return '';
        }

        $title = '';
        $content = File::get($viewPath);

        // 1. Match @section('title', translate('...'))
        if (preg_match("/@section\\('title',\\s*translate\\(['\"]([^'\"]*)['\"]\\)\\)/", $content, $matches)) {
            $title = $matches[1];
        } // 2. Match fallback pattern
        elseif (preg_match("/@section\\('title',\\s*translate\\(['\"](.*?)['\"]\\)/", $content, $matches)) {
            $title = $matches[1];
        } // 3. Match <span class="page-header-title">...translate('...')...</span>
        elseif (preg_match_all("/<span[^>]*class=[\"'][^\"']*page-header-title[^\"']*[\"'][^>]*>(.*?)<\/span>/s", $content, $spanMatches)) {
            $translated = [];

            foreach ($spanMatches[1] as $spanContent) {
                if (preg_match_all("/translate\\(['\"]([^'\"]+)['\"]\\)/", $spanContent, $transMatches)) {
                    foreach ($transMatches[1] as $t) {
                        $translated[] = $t;
                    }
                }
            }

            if (!empty($translated)) {
                $title = implode(' ', array_unique($translated));
            }
        }

        $words = preg_split('/\s+/', $title);
        $words = array_unique($words);

        $cleaned = array_map(function ($word) {
            $word = preg_replace('/[^\p{L}\p{N}_\s]/u', '', $word);
            return trim($word);
        }, $words);

        $cleaned = array_filter($cleaned);
        return implode(', ', $cleaned);
    }
    private function controllerReturnsView(string $filename, int $startLine, int $endLine): bool
    {
        $file = file($filename);
        $codeBody = implode('', array_slice($file, $startLine - 1, $endLine - $startLine + 1));


        if (preg_match("/return\s+(view|View)::|\bview\(/i", $codeBody)) {
            if (
                preg_match("/return\s+(response|redirect|new\s+Response|JsonResponse|\\\$this->.*response)/i", $codeBody)
            ) {
                return false;
            }
            return true;
        }

        return false;
    }

    private function extractKeywordsFromView(string $viewPath, array &$processedViews = []): array|string
    {
        if (in_array($viewPath, $processedViews)) {
            return [];
        }

        $processedViews[] = $viewPath;
        if (!File::exists($viewPath)) {
            return [];
        }

        $textContent = $this->extractTextContent($viewPath);
        // String to array
        $words = preg_split('/\s+/', $textContent);
        $words = array_unique($words);

        // Clean each word
        $cleaned = array_map(function ($word) {
            // Keep letters, numbers, underscores, and whitespace
            $word = preg_replace('/[^\p{L}\p{N}_\s]/u', '', $word);
            return trim($word);
        }, $words);

        // Remove empty
        $cleaned = array_filter($cleaned);
        return implode(', ', $cleaned);
    }


    private function additionalItemsWithoutKeywords(): array
    {
        $result = [];
        $additionalPages = [
            [
                'page_title' => 'in_House_Product_List',
                'key' => base64_encode('admin/products/list/in-house'),
                'uri' => 'admin/products/list/in-house',
                'uri_count' => count(explode('/', 'admin/products/list/in-house')),
                'method' => 'GET',
                'view' => 'admin-views.product.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'vendor_Product_List',
                'key' => base64_encode('admin/products/list/vendor'),
                'uri' => 'admin/products/list/vendor',
                'uri_count' => count(explode('/', 'admin/products/list/vendor')),
                'method' => 'GET',
                'view' => 'vendor-views.product.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'packaging_Orders',
                'key' => base64_encode('admin/products/list/processing'),
                'uri' => 'admin/products/list/processing',
                'uri_count' => count(explode('/', 'admin/products/list/processing')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'failed_to_Deliver_Orders',
                'key' => base64_encode('admin/products/list/failed'),
                'uri' => 'admin/products/list/failed',
                'uri_count' => count(explode('/', 'admin/products/list/failed')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'all_Orders',
                'key' => base64_encode('admin/products/list/all'),
                'uri' => 'admin/products/list/all',
                'uri_count' => count(explode('/', 'admin/products/list/all')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'pending_Orders',
                'key' => base64_encode('admin/orders/list/pending'),
                'uri' => 'admin/orders/list/pending',
                'uri_count' => count(explode('/', 'admin/orders/list/pending')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'confirmed_Orders',
                'key' => base64_encode('admin/orders/list/confirmed'),
                'uri' => 'admin/orders/list/confirmed',
                'uri_count' => count(explode('/', 'admin/orders/list/confirmed')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'packaging_Orders',
                'key' => base64_encode('admin/orders/list/processing'),
                'uri' => 'admin/orders/list/processing',
                'uri_count' => count(explode('/', 'admin/orders/list/processing')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'out_of_delivery_Orders',
                'key' => base64_encode('admin/orders/list/out_for_delivery'),
                'uri' => 'admin/orders/list/out_for_delivery',
                'uri_count' => count(explode('/', 'admin/orders/list/out_for_delivery')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'delivered_Orders',
                'key' => base64_encode('admin/orders/list/delivered'),
                'uri' => 'admin/orders/list/delivered',
                'uri_count' => count(explode('/', 'admin/orders/list/delivered')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'returned_Orders',
                'key' => base64_encode('admin/orders/list/returned'),
                'uri' => 'admin/orders/list/returned',
                'uri_count' => count(explode('/', 'admin/orders/list/returned')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'failed_to_deliver_Orders',
                'key' => base64_encode('admin/orders/list/failed'),
                'uri' => 'admin/orders/list/failed',
                'uri_count' => count(explode('/', 'admin/orders/list/failed')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'canceled_Orders',
                'key' => base64_encode('admin/orders/list/canceled'),
                'uri' => 'admin/orders/list/canceled',
                'uri_count' => count(explode('/', 'admin/orders/list/canceled')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
            [
                'page_title' => 'canceled_Orders',
                'key' => base64_encode('admin/refund-section/refund/list/pending'),
                'uri' => 'admin/refund-section/refund/list/pending',
                'uri_count' => count(explode('/', 'admin/refund-section/refund/list/pending')),
                'method' => 'GET',
                'view' => 'admin-views.order.list',
                'keywords' => ''
            ],
        ];

        foreach ($additionalPages as $page) {
            $fullPath = $this->getFullViewPath($page['view']);
            $keywords = File::exists($fullPath) && !empty($this->extractKeywordsFromView($fullPath)) ? $this->extractKeywordsFromView($fullPath) : $this->humanizeTranslationKey($page['page_title']);
            $result[] = [
                'page_title' => $page['page_title'],
                'page_title_value' => $page['page_title'],
                'key' => $page['key'],
                'uri' => $page['uri'],
                'uri_count' => $page['uri_count'],
                'method' => $page['method'],
                'view' => $page['view'],
                'keywords' => $keywords
            ];
        }
        return $result;
    }

    private function humanizeTranslationKey(string $key): string
    {
        $key = str_replace('_', ' ', $key);
        return ucwords(strtolower($key));
    }
}
