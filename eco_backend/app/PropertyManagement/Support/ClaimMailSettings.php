<?php

namespace App\PropertyManagement\Support;

use Illuminate\Support\Facades\Cache;

class ClaimMailSettings
{
    public const DEFAULT_BANK_IBAN = 'SA82550000000R0877300433';

    public const DEFAULT_CC_EMAIL = 'farook@adv-line.com';

    public static function bankIban(): string
    {
        $value = Cache::get('settings.claim_bank_iban');

        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return self::DEFAULT_BANK_IBAN;
    }

    /**
     * @return array<int, string>
     */
    public static function ccEmails(): array
    {
        $cached = Cache::get('settings.claim_cc_email');

        if ($cached !== null && $cached !== '') {
            return self::parseEmailList((string) $cached);
        }

        $fromConfig = config('mail.customer_cc', []);

        if (!empty($fromConfig)) {
            return array_values(array_filter($fromConfig));
        }

        return [self::DEFAULT_CC_EMAIL];
    }

    public static function ccEmailsAsString(): string
    {
        return implode(', ', self::ccEmails());
    }

    /**
     * @return array<int, string>
     */
    private static function parseEmailList(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
