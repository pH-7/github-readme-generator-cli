<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

namespace PH7\PhpReadmeGeneratorFile;

use PH7\PhpReadmeGeneratorFile\Command\Markdown\License;

final class DefaultValue
{
    public const AUTHOR = 'Pierre-Henry Soria';
    public const EMAIL = 'hi@ph7.me';
    public const GITHUB = 'pH-7';
    public const LICENSE_CODE = License::MIT_CODE;
    public const DESTINATION_FILE = ROOT_DIR . '/tmp';
    public const GRAVATAR_SIZE = 200;
}
