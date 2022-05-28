<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Tests\Command\Markdown;

use PH7\PhpReadmeGeneratorFile\Command\Markdown\License;
use PHPUnit\Framework\TestCase;

final class LicenseTest extends TestCase
{
    /**
     * @dataProvider licenseCodeProvider
     */
    public function testLinkWithValidLicenseCode(string $licenseCode): void
    {
        $actual = License::getLicenseLink($licenseCode);
        $this->assertsame("https://opensource.org/licenses/{$licenseCode}", $actual);
    }

    public function testLinkWithInvalidLicenseCode(): void
    {
        $actual = License::getLicenseLink('INVALID');
        $this->assertsame('https://opensource.org/licenses', $actual);
    }

    public function licenseCodeProvider(): array
    {
        return [License::CODES];
    }

}
