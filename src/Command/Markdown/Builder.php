<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

use PH7\PH2Gravatar\Gravatar\Image;
use PH7\PhpReadmeGeneratorFile\DefaultValue;

class Builder
{
    private string $projectName;

    private string $heading;

    private string $description;

    private string $requirements;

    private string $authorName;

    private string $authorEmail;

    private string $authorWebpage;

    private string $licenseType;

    private string $licenseLink;

    private string $gravatarImage;

    private string $githubUsername;

    public function __construct(array $data)
    {
        $this->projectName = $data['name'];
        $this->heading = $data['heading'];
        $this->description = $data['description'];
        $this->requirements = $data['requirements'];
        $this->authorName = $data['author'];
        $this->authorEmail = $data['email'];
        $this->authorWebpage = $data['webpage'];
        $this->licenseType = $data['license'];
        $this->licenseLink = License::getLicenseLink($data['license']);
        $this->gravatarImage = $this->getGravatar();
        $this->githubUsername = $data['github'];
    }

    public function save(string $path): bool|int
    {
        return file_put_contents($path, $this->getContents());
    }

    private function getContents(): string
    {
        return $this->parse(file_get_contents(__DIR__ . '/view/readme-template.md'));
    }

    private function getGravatar(): string
    {
        return Image::get($this->authorEmail, ['size' => DefaultValue::GRAVATAR_SIZE]);
    }

    private function parse(string $contents): string
    {
        return str_ireplace(
            [
                ':PROJECT-NAME:',
                ':HEADING:',
                ':DESCRIPTION:',
                ':REQUIREMENTS:',
                ':AUTHOR-NAME:',
                ':AUTHOR-EMAIL:',
                ':AUTHOR-URL:',
                ':LICENSE-NAME:',
                ':LICENSE-LINK:',
                ':GRAVATAR-IMAGE:',
                ':GITHUB-USERNAME:',
            ],
            [
                $this->projectName,
                $this->heading,
                $this->description,
                $this->requirements,
                $this->authorName,
                $this->authorEmail,
                $this->authorWebpage,
                $this->licenseType,
                $this->licenseLink,
                $this->gravatarImage,
                $this->githubUsername,
            ],
            $contents
        );
    }
}
