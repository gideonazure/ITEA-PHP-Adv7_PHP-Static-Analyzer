<?php

declare(strict_types=1);

namespace ITEA\PhpStaticAnalyzer\Command;

use ITEA\PhpStaticAnalyzer\Analyzer\ClassesCreatedByDeveloperAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Volodymyr Kupriienko <vladimir.kuprienko@itea.ua>
 */
final class ClassesCreatedByDeveloperCommand extends Command
{
    protected static $defaultName = 'classes-created-by';
    private ClassesCreatedByDeveloperAnalyzer $analyzer;

    public function __construct(ClassesCreatedByDeveloperAnalyzer  $analyzer)
    {
        parent::__construct();
        $this->analyzer = $analyzer;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Gets count of classes created by needed developer')
            ->addArgument(
                'project_src_path',
                InputArgument::REQUIRED,
                'Absolute path to PHP project source code to analyze.'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'E-mail address of needed developer.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectSrcPath = $input->getArgument('project_src_path');
        $email = $input->getArgument('email');

        $count = $this->analyzer->analyze($email, $projectSrcPath);

        $output->writeln(\sprintf('Developer with email <info>%s</info> created <info>%d</info> classes.', $email, $count));

        return self::SUCCESS;
    }
}
