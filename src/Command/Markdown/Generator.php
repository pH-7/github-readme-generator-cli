<?php

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

use PH7\PhpReadmeGeneratorFile\Command\Exception\EmptyFieldException;
use PH7\PhpReadmeGeneratorFile\DefaultValue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class Generator extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('markdown:generate')
            ->addArgument(
                'title',
                InputArgument::REQUIRED,
                'Project Name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $data = $this->treatFields($input, $output);

        if (is_array($data)) {
            if ($this->finalConfirmation($helper, $input, $output)) {
                $path = $helper->ask($input, $output, $this->promptDestinationFile());
                $path = is_string($path) && strlen($path) > 2 ? realpath($path) : ROOT_DIR . '/tmp';
                $filename = sprintf('README-%s.md', date('Y-m-d'));

                if (is_dir($path)) {
                    $fileBuilder = new Builder($data);

                    $fullpath = $path . DIRECTORY_SEPARATOR . $filename;
                    $fileBuilder->save($fullpath);

                    $output->writeln(
                        $io->success(sprintf('File successfully saved at: %s', $fullpath))
                    );

                    return Command::SUCCESS;
                } else {
                    $output->writeln(
                        $io->error(sprintf('Oops. The path "%s" doesn\'t exist.', $path))
                    );

                    return Command::INVALID;
                }
            }
        }

        return Command::FAILURE;
    }

    private function treatFields(InputInterface $input, OutputInterface $output): array|int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        try {
            $heading = $this->promptHeading($io);
            $description = $this->promptDescription($io);
            $author = $this->promptAuthor($io);
            $email = $this->promptEmail($io);
            $license = $this->promptLicense($helper, $input, $output);

            return [
                'title' => $input->getArgument('title'),
                'heading' => $heading,
                'description' => $description,
                'author' => $author,
                'email' => $email,
                'license' => $license
            ];
        } catch (EmptyFieldException $e) {
            $io->warning($e->getMessage());

            return Command::INVALID;;
        }
    }

    private function promptHeading(SymfonyStyle $io): string
    {
        $heading = $io->ask('Project Heading');

        if (!$this->isFieldFilled($heading)) {
            throw new EmptyFieldException('Mention the README heading.');
        }

        return $heading;
    }

    private function promptDescription(SymfonyStyle $io): string
    {
        $description = $io->ask('Project Description');

        if (!$this->isFieldFilled($description)) {
            throw new EmptyFieldException('Describe a bit your project.');
        }

        return $description;
    }

    private function promptAuthor(SymfonyStyle $io): string
    {
        $authorName = $io->ask('Author Name');

        if (!$this->isFieldFilled($authorName)) {
            throw new EmptyFieldException('Author name is required.');
        }

        return $authorName;
    }

    private function promptEmail(SymfonyStyle $io): string
    {
        $email = $io->ask('Author Email');

        if (!$this->isFieldFilled($email)) {
            throw new EmptyFieldException('Author email is required.');
        }

        return $email;
    }

    private function promptLicense(HelperInterface $helper, InputInterface $input, OutputInterface $output): string
    {
        $question = new ChoiceQuestion(
            'License',
            License::CODES,
            DefaultValue::LICENSE
        );

        $question->setErrorMessage('Select a valid license type 🤠');

        $license = $helper->ask($input, $output, $question);

        if (!$this->isFieldFilled($license)) {
            throw new EmptyFieldException('License type is required.');
        }

        return $license;
    }

    private function finalConfirmation(HelperInterface $helper, InputInterface $input, OutputInterface $output): bool
    {
        $question = new ConfirmationQuestion('Are you happy to generate the README? [y/m]', true);

        return (bool)$helper->ask($input, $output, $question);
    }

    private function promptDestinationFile(): Question
    {
        return new Question(
            'Path Destination Readme File',
        //DefaultValue::DESTINATION_FILE
        );
    }

    private function isFieldFilled($string): bool
    {
        return !empty($string) && strlen($string) > 0;
    }
}
