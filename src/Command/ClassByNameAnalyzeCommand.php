<?php

declare(strict_types=1);

/*
 * This file is part of the "default-project" package.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ITEA\PhpStaticAnalyzer\Command;

use ITEA\PhpStaticAnalyzer\Analyzer\ClassByNameAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for working with ClassByNameAnalyzer and outputting the result.
 *
 * @author Alexey Sk <gid.azure@gmail.com>
 */
final class ClassByNameAnalyzeCommand extends Command
{
    protected static $defaultName = 'class-info-by-name';
    private ClassByNameAnalyzer $analyzer;

    public function __construct(ClassByNameAnalyzer $analyzer)
    {
        parent::__construct();
        $this->analyzer = $analyzer;
    }

    /**
     * Configure method for creating console command.
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
     * Execute method for call analyze method from ClassByNameAnalyzer.
     *
     * @param InputInterface  $input  Input interface for pass arguments into console command
     * @param OutputInterface $output Output interface for display result of console command
     *
     * @return int Returnable result value
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectSrcPath = $input->getArgument('project_src_path');
        $className = $input->getArgument('class_name');
        $info = $this->analyzer->analyze($className, $projectSrcPath);

        $classProperties = $info['properties'];
        $classMethods = $info['methods'];

        $infoResult = "Class: {$info['name']} is {$info['type']} " . \PHP_EOL . '
                Properties: ' . \PHP_EOL . '
                    public: ' . $classProperties['public'] . \PHP_EOL . '
                    protected: ' . $classProperties['protected'] . \PHP_EOL . '
                    private: ' . $classProperties['private'] . \PHP_EOL . '
                Methods:
                    public: ' . $classMethods['public'] . \PHP_EOL . '
                    protected: ' . $classMethods['protected'] . \PHP_EOL . '
                    private: ' . $classMethods['private'] . \PHP_EOL;

        $output->writeln($infoResult);

        return self::SUCCESS;
    }
}
