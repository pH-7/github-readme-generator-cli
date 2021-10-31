<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Tests\Command\Markdown;

use PH7\PhpReadmeGeneratorFile\Command\Markdown\GeneratorCommand;
use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GeneratorCommandTest extends TestCase
{
    private OutputInterface|Phake_IMock $input;

    private OutputInterface|Phake_IMock $output;
    
    private OutputInterface|Phake_IMock $io;
    
    private GeneratorCommand $generator;
    
    protected function setUp(): void
    {
        $this->input = Phake::mock(InputInterface::class);
        $this->output = Phake::mock(OutputInterface::class);
        $this->io = Phake::mock(SymfonyStyle::class);
        
        $this->generator = new GeneratorCommand();
    }

    public function testExecute(): void
    {
        // Phake::when($this->io)->getHelper
        
        $this->generator->execute($this->input, $this->output);
    }
}
