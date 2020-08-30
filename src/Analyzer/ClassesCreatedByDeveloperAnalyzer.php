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

namespace ITEA\PhpStaticAnalyzer\Analyzer;

use ITEA\PhpStaticAnalyzer\Util\PhpFileUtil;
use PhpCsFixer\Finder;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;

/**
 * @author Volodymyr Kupriienko <vladimir.kuprienko@itea.ua>
 */
final class ClassesCreatedByDeveloperAnalyzer
{
    private DocBlockFactory $docBlockFactory;

    public function __construct()
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function analyze(string $email, string $projectSrcPath): int
    {
        $finder = $this->getFinder($projectSrcPath);
        $counter = 0;

        foreach ($finder as $phpFilePath) {
            $classNamespace = PhpFileUtil::getClassNameFromFile($phpFilePath->getRealPath());
            $docBlock = $this->getDocBlockFromClass($classNamespace);

            if (null !== $docBlock && $this->isClassAuthor($docBlock, $email)) {
                ++$counter;
            }
        }

        return $counter;
    }

    private function getFinder(string $projectSrcPath): Finder
    {
        return Finder::create()
            ->in($projectSrcPath)
            ->name('/^[A-Z]+\.php$/')
        ;
    }

    private function getDocBlockFromClass(string $classNamespace): ?DocBlock
    {
        try {
            $reflector = new \ReflectionClass($classNamespace);
        } catch (\ReflectionException $e) {
            return null;
        }

        $docComment = $reflector->getDocComment();

        if (false === $docComment) {
            return null;
        }

        return $this->docBlockFactory->create($docComment);
    }

    private function isClassAuthor(DocBlock $docBlock, string $email): bool
    {
        foreach ($docBlock->getTagsByName('author') as $authorTag) {
            if ($authorTag->getEmail() === $email) {
                return true;
            }
        }

        return false;
    }
}
