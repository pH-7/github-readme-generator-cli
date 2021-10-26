<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

final class License
{
    public const ISC_CODE = 'ISC';
    public const MIT_CODE = 'MIT';
    public const GPL_CODE = 'GPL';
    public const BSD_CODE = 'BSD';
    public const MPL_CODE = 'MPL';
    public const AGPL_CODE = 'AGPL';

    private const ISC_URL = 'https://opensource.org/licenses/ISC';
    private const MIT_URL = 'https://opensource.org/licenses/MIT';
    private const GPL_URL = 'https://www.gnu.org/licenses/gpl.html';
    private const BSD_URL = 'https://opensource.org/licenses/BSD-3-Clause';
    private const MPL_URL = 'MPhttps://www.mozilla.org/en-US/MPL';
    private const AGPL_URL = 'https://www.gnu.org/licenses/agpl.html';
    private const DEFAULT_URL = 'https://opensource.org/licenses';

    private const LICENSES = [
        self::ISC_CODE => self::ISC_URL,
        self::MIT_CODE => self::MIT_URL,
        self::GPL_CODE => self::GPL_URL,
        self::BSD_CODE => self::BSD_URL,
        self::MPL_CODE => self::MPL_URL,
        self::AGPL_CODE => self::AGPL_URL,
    ];

    public const CODES = [
        self::ISC_CODE,
        self::MIT_CODE,
        self::GPL_CODE,
        self::BSD_CODE,
        self::MPL_CODE,
        self::AGPL_CODE,
    ];

    public static function getLicenseLink(string $licenseType): string
    {
        return in_array($licenseType, self::CODES) ? self::LICENSES[$licenseType] : self::DEFAULT_URL;
    }
}
