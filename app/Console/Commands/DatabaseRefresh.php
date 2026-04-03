<?php

namespace App\Console\Commands;

use App\Models\BusinessSetting;
use App\Traits\PushNotificationTrait;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Madnest\Madzipper\Facades\Madzipper;

class DatabaseRefresh extends Command
{
    use PushNotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database after a certain time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $this->demoResetNotification();
        Artisan::call('db:wipe');
        Artisan::call('cache:clear');

        Cache::put('demo_database_refresh', 1, 120);

        $sql_path = base_path('demo/database.sql');
        DB::unprepared(file_get_contents($sql_path));
        File::deleteDirectory('storage/app/public');
        Madzipper::make('demo/public.zip')->extractTo('storage/app');

        try {
            if (!in_array(request()->ip(), ['127.0.0.1', '::1'])) {
                $recaptcha = BusinessSetting::where('type', 'recaptcha')->first()?->value ?? '';
                $recaptcha =  is_string($recaptcha) ? json_decode($recaptcha, true) : $recaptcha;
                $value = json_encode(['status' => 0, 'site_key' => $recaptcha['site_key'], 'secret_key' => $recaptcha['secret_key']]);
                BusinessSetting::where('type', 'recaptcha')->update(['type' =>'recaptcha', 'value' => $value]);
            }
        } catch (Exception $exception) {}

        Cache::forget('demo_database_refresh');
    }
}
