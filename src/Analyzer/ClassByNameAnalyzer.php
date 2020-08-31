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

/**
 * Class witch analyze class info by class name.
 *
 * @author Alexey Sk <gid.azure@gmail.com>
 */
final class ClassByNameAnalyzer
{
    private const CLASS_TYPE_ABSTRACT = 'Abstract';
    private const CLASS_TYPE_FINAL = 'Final';
    private const CLASS_TYPE_NORMAL = 'Normal';

    /**
     * Method analyze class structure and return info about count of properties and methods.
     *
     * @param string $className      The name of the class on which to analyze
     * @param string $projectSrcPath Working directory path
     *
     * @return array A string describing the number of methods and parameters
     */
    public function analyze(string $className, string $projectSrcPath = '/'): array
    {
        $outInfo = [];
        $classInfo = $this->getClassReflectionInstance($className);

        if ($classInfo->getName() == $className) {
            $properties = $this->getClassProperties($classInfo);
            $methods = $this->getClassMethods($classInfo);

            $outInfo = [
                'name' => $classInfo->getShortName(),
                'type' => $this->getClassType($classInfo),
                'properties' => [
                    'public' => \count($properties['public']),
                    'private' => \count($properties['private']),
                    'protected' => \count($properties['protected']),
                ],
                'methods' => [
                    'public' => \count($methods['public']),
                    'private' => \count($methods['private']),
                    'protected' => \count($methods['protected']),
                ],
            ];
        }

        return $outInfo;
    }

    /**
     * Method create Reflectiop class instance.
     *
     * @param string $classNamespace Name of class for create instant
     *
     * @return \ReflectionClass|string Reflection object with data about passed class
     */
    private function getClassReflectionInstance(string $classNamespace)
    {
        if (!\class_exists($classNamespace)) {
            throw new \ReflectionException('Passed class not found. Please, check argument');
        }

        return new \ReflectionClass($classNamespace);
    }

    /**
     * Method determines the type of the passed class.
     *
     * @param object $classInfo The class the type of which you want to determine
     *
     * @return string Returnable class type
     */
    private function getClassType(object $classInfo): string
    {
        if ($classInfo->isAbstract()) {
            return self::CLASS_TYPE_ABSTRACT;
        }

        if ($classInfo->isFinal()) {
            return self::CLASS_TYPE_FINAL;
        }

        return self::CLASS_TYPE_NORMAL;
    }

    /**
     * Method witch create array with list of class methods grouped by type.
     *
     * @param object $classInfo The class the type of which you want to determine
     *
     * @return array Returnable array of grouped methods
     */
    private function getClassMethods(object $classInfo): array
    {
        return [
              'private' => $classInfo->getMethods(\ReflectionMethod::IS_PRIVATE),
              'public' => $classInfo->getMethods(\ReflectionMethod::IS_PUBLIC),
              'protected' => $classInfo->getMethods(\ReflectionMethod::IS_PROTECTED),
          ];
    }

    /**
     * Method witch create array with list of class properties grouped by type.
     *
     * @param object $classInfo The class the type of which you want to determine
     *
     * @return array Returnable array of grouped methods
     */
    private function getClassProperties(object $classInfo): array
    {
        return [
            'private' => $classInfo->getProperties(\ReflectionProperty::IS_PRIVATE),
            'public' => $classInfo->getProperties(\ReflectionProperty::IS_PUBLIC),
            'protected' => $classInfo->getProperties(\ReflectionProperty::IS_PROTECTED),
        ];
    }
}
