<?php

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

use PH7\PhpReadmeGeneratorFile\DefaultValue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Generator extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('generate')
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                'Project Name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $heading = $helper->ask($input, $output, $this->promptHeading());
        $description = $helper->ask($input, $output, $this->promptDescription());
        $author = $helper->ask($input, $output, $this->promptAuthor());
        $email = $helper->ask($input, $output, $this->promptEmail());
        $license = $helper->ask($input, $output, $this->promptLicense());

        if ($this->finalConfirmation()) {
            $path = $helper->ask($input, $output, $this->promptDestinationFile());

            if (file_exists($path)) {
                $fileBuilder = new Build([
                    'title' => $input->getOption('title'),
                    'heading' => $heading,
                    'description' => $description,
                    'author' => $author,
                    'email' => $email,
                    'license' => $license
                ]);

                $fileBuilder->save($path);

                $output->writeln('<info>File successfully saved at: ' . $path . '</info>');

                return Command::SUCCESS;
            } else {
                $output->writeln('<error>Path doesn\'t exist.</error>');

                return Command::FAILURE;
            }
        }

        return Command::FAILURE;
    }

    private function promptHeading(): Question
    {
        return new Question(
            'Project Heading',
        );
    }

    private function promptDescription(): Question
    {
        return new Question(
            'Project Description',
        );
    }

    private function promptAuthor(): Question
    {
        return new Question(
            'Author Name',
            DefaultValue::AUTHOR
        );
    }

    private function promptEmail(): Question
    {
        return new Question(
            'Author Email',
            DefaultValue::EMAIL
        );
    }

    private function promptLicense(): ChoiceQuestion
    {
        return new ChoiceQuestion(
            'License',
            ['ISC', 'MIT', 'GPL', 'BSD'],
            DefaultValue::LICENSE
        );
    }

    private function promptDestinationFile(): Question
    {
        return new Question(
            'Path Destination Readme File',
            //DefaultValue::DESTINATION_FILE
        );
    }

    private function finalConfirmation(HelperInterface $helper, InputInterface $input, OutputInterface $output): bool
    {
        $question = new ConfirmationQuestion('Are you happy to generate the README?', false);

        return (bool)$helper->ask($input, $output, $question);
    }
}