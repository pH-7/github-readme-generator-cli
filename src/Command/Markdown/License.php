<?php

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

final class License
{
    private const ISC = 'ISC';
    private const MIT = 'MIT';
    private const GPL = 'GPL';
    private const BSD = 'BSD';
    private const MPL = 'MPL';
    private const AGPL = 'AGPL';

    public const CODES = [
        self::ISC,
        self::MIT,
        self::GPL,
        self::BSD,
        self::MPL,
        self::AGPL,
    ];

    public static function getLicenseLink($licenseType)
    {
        switch ($licenseType) {
            case self::ISC:
                return 'https://opensource.org/licenses/ISC';

            case self::MIT:
                return 'https://opensource.org/licenses/MIT';

            case self::GPL:
                return 'https://www.gnu.org/licenses/gpl.html';

            case self::MPL:
                return 'https://www.mozilla.org/en-US/MPL/';

            case self::AGPL:
                return 'https://www.gnu.org/licenses/agpl.html';

            default:
                return 'https://opensource.org/licenses/';
        }
    }
}
