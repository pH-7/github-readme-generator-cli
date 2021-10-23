<?php

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

class Builder
{
    private string $title;

    private string $heading;

    private string $description;

    private string $authorName;

    private string $authorEmail;

    private string $licenseType;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->heading = $data['heading'];
        $this->description = $data['description'];
        $this->authorName = $data['author'];
        $this->authorEmail = $data['email'];
        $this->licenseType = $data['license'];
    }

    public function save(string $path): bool|int
    {
        return file_put_contents($path, $this->getContents());
    }

    private function getContents(): string
    {
        return $this->parse(file_get_contents(__DIR__ . '/view/readme-template.md'));
    }

    private function parse(string $contents): string
    {
        return str_ireplace(
            [
                '[TITLE]',
                '[HEADING]',
                '[DESCRIPTION]',
                '[AUTHOR]',
                '[EMAIL]',
                '[LICENSE]',
            ],
            [
                $this->title,
                $this->heading,
                $this->description,
                $this->authorName,
                $this->authorEmail,
                $this->licenseType
            ],
            $contents
        );
    }
}
