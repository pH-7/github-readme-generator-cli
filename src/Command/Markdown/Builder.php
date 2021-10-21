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

    public function save(string $path): bool
    {
        return file_put_contents($path, $this->getContents())
    }

    private function getContents()
    {
        return include __DIR__ . '/view/readme-view.md';
    }
}
