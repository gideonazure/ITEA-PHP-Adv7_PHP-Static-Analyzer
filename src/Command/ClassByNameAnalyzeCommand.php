<?php

declare(strict_types=1);

namespace ITEA\PhpStaticAnalyzer\Command;

use ITEA\PhpStaticAnalyzer\Analyzer\ClassByNameAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for working with ClassByNameAnalyzer and outputting the result
 *
 * @author Alexey Sk <gid.azure@gmail.com>
 */
final class ClassByNameAnalyzeCommand extends Command
{
    protected static $defaultName = 'class-info-by-name';
    private ClassByNameAnalyzer $analyzer;

    public function __construct(ClassByNameAnalyzer  $analyzer)
    {
        parent::__construct();
        $this->analyzer = $analyzer;
    }


    /**
     * Configure method for creating console command
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Get info about class properties (count) and methods(count)')
            ->addArgument(
                'project_src_path',
                InputArgument::REQUIRED,
                'Absolute path to PHP project source code to analyze.'
            )
            ->addArgument(
                'class_name',
                InputArgument::REQUIRED,
                'The name of the class on which to analyze'
            )
        ;
    }

    /**
     * Execute method for call analyze method from ClassByNameAnalyzer
     *
     * @param InputInterface $input Input interface for pass arguments into console command
     * @param OutputInterface $output Output interface for display result of console command
     * @return int Returnable result value
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectSrcPath = $input->getArgument('project_src_path');
        $className = $input->getArgument('class_name');

        $info = $this->analyzer->analyze($className, $projectSrcPath);

        $output->writeln($info);

        return self::SUCCESS;
    }
}
