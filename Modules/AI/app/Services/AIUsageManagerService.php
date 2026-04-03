<?php

namespace Modules\AI\app\Services;

use Modules\AI\app\Exceptions\UsageLimitException;
use Modules\AI\app\Models\AISetting;
use Modules\AI\app\Models\AISettingLog;
use Modules\AI\app\Utils\CurrentAuthUser;

class AIUsageManagerService
{

    /**
     * @throws UsageLimitException
     */
    public function checkUsageLimits(AISettingLog $log, AISetting $provider, ?string $imageUrl, ?string $section = null): void
    {
        $providerGenerateLimit = env('APP_MODE') == 'demo' ? 10 : $provider->generate_limit;
        $generateLimit = env('APP_MODE') == 'demo' ? 10 : $provider?->image_upload_limit;
        $remainingImgAction = $generateLimit <= 0 ? 0 : ($log?->total_image_generated_count < $generateLimit ? ($generateLimit - $log->total_image_generated_count) : 0);

        if (!empty($imageUrl)) {
            if ($remainingImgAction <= 0) {
                throw new UsageLimitException('Image upload limit reached for this seller.');
            }
        } else {
            if ($section !== 'generate_title_from_image' &&
                $log->total_generated_count >= $providerGenerateLimit) {
                throw new UsageLimitException('Text generation limit reached for this seller.');
            }
        }
    }

    public function incrementUsage(AISettingLog $log, ?string $imageUrl, ?string $section = ''): void
    {
        if (!empty($imageUrl)) {
            $log->total_image_generated_count += 1;
        }
        $log->total_generated_count += 1;
        $usage = $log->section_usage ?? [];
        $usage[$section] = ($usage[$section] ?? 0) + 1;
        $log->section_usage = $usage;
        $log->save();
    }

    public function getOrCreateLog(AISetting $activeProvider): AISettingLog
    {
        $sellerId = CurrentAuthUser::id();
        $aiSettingLog = AISettingLog::where('seller_id', $sellerId)->first();
        if (!$aiSettingLog) {
            $aiSettingLog = new AISettingLog();
            $aiSettingLog->seller_id = $sellerId;
            $aiSettingLog->total_generated_count = 0;
            $aiSettingLog->total_image_generated_count = 0;
            $aiSettingLog->limit_at_time = $activeProvider->generate_limit;
        }
        return $aiSettingLog;
    }

    public function getGenerateRemainingCount(): int
    {
        $sellerId = CurrentAuthUser::vendor();
        $aiSettingLog = AISettingLog::where('seller_id', $sellerId->id)->first();
        $generateLimit = env('APP_MODE') == 'demo' ? 10 : (AISetting::first()?->generate_limit ?? 0);
        if (!$aiSettingLog) {
            return $generateLimit;
        }
        return $generateLimit <= 0 ? 0 : ($aiSettingLog && ($aiSettingLog?->total_generated_count < $generateLimit) ? ($generateLimit - $aiSettingLog->total_generated_count) : 0);
    }

}
