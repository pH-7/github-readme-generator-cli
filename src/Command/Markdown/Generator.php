<?php
/**
 * Copyright (c) Pierre-Henry Soria <hi@ph7.me>
 * MIT License - https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace PH7\PhpReadmeGeneratorFile\Command\Markdown;

use PH7\PhpReadmeGeneratorFile\Command\Exception\EmptyFieldException;
use PH7\PhpReadmeGeneratorFile\Command\Exception\InvalidInputException;
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
        $this->setName('markdown:generate');
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
                $filename = sprintf('README-%s.md', date('Y-m-d H:i'));

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
            $name = $this->promptName($io);
            $heading = $this->promptHeading($io);
            $description = $this->promptDescription($io);
            $requirements = $this->promptRequirements($io);
            $author = $this->promptAuthor($io);
            $email = $this->promptEmail($io);
            $webpage = $this->promptHomepageUrl($io);
            $githubUsername = $this->promptGithub($io);
            $license = $this->promptLicense($helper, $input, $output);

            return [
                'name' => $name,
                'heading' => $heading,
                'description' => $description,
                'requirements' => $requirements,
                'author' => $author,
                'email' => $email,
                'webpage' => $webpage,
                'github' => $githubUsername,
                'license' => $license
            ];
        } catch (EmptyFieldException $e) {
            $io->warning($e->getMessage());

            return Command::INVALID;
        } catch (InvalidInputException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function promptName(SymfonyStyle $io): string
    {
        $name = $io->ask('Project Name');

        if (!$this->isFieldFilled($name)) {
            throw new EmptyFieldException('Mention a name for your project 😺');
        }

        return $name;
    }

    private function promptHeading(SymfonyStyle $io): string
    {
        $heading = $io->ask('Project Heading/Summary');

        if (!$this->isFieldFilled($heading)) {
            throw new EmptyFieldException('Mention the README heading or a small summary.');
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

    private function promptRequirements(SymfonyStyle $io): string
    {
        $requirements = $io->ask('Requirements / Installation?');

        if (!$this->isFieldFilled($requirements)) {
            throw new EmptyFieldException('What are the requirements/Installation steps for this project?');
        }

        return $requirements;
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
        $email = $io->ask('Valid Author Email (will also be used for your gravatar)');

        if (!$this->isFieldFilled($email)) {
            throw new EmptyFieldException('Author email is required.');
        }


        if (!$this->isValidEmail($email)) {
            throw new InvalidInputException('Please mention a valid email 😀');
        }

        return $email;
    }

    private function promptHomepageUrl(SymfonyStyle $io): string
    {
        $webpage = $io->ask('Valid Author Webpage');


        if (!$this->isFieldFilled($webpage)) {
            throw new EmptyFieldException('Can you mention your homepage (.e.g website, GitHub profile, etc).');
        }

        if (!$this->isValidUrl($webpage)) {
            throw new InvalidInputException('Please mention a valid website URL 😄');
        }

        return $webpage;
    }

    private function promptGithub(SymfonyStyle $io): string
    {
        $github = $io->ask('GitHub Username (github.com/<username>)');

        if (!$this->isFieldFilled($github)) {
            throw new EmptyFieldException('GitHub nickname is required.');
        }

        return $github;
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

    private function isFieldFilled(?string $string): bool
    {
        return !empty($string) && strlen($string) > 0;
    }

    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
