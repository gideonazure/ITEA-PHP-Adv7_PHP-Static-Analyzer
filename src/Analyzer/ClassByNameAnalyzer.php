<?php

declare(strict_types=1);

namespace ITEA\PhpStaticAnalyzer\Analyzer;

use ITEA\PhpStaticAnalyzer\Util\PhpFileUtil;
use PhpCsFixer\Finder;

/**
 * Class witch analyze class info by class name
 *
 * @author Alexey Sk <gid.azure@gmail.com>
 */
final class ClassByNameAnalyzer
{

    /**
     * Method analyze class structure and return info about count of properties and methods
     *
     * @param string $className  The name of the class on which to analyze
     * @param string $projectSrcPath Working directory path
     * @return string A string describing the number of methods and parameters
     */
    public function analyze(string $className, string $projectSrcPath): string
    {
        $finder = $this->getFinder($projectSrcPath);
        $outInfo = "";

        foreach ($finder as $phpFilePath) {

            $classNamespace = PhpFileUtil::getClassNameFromFile($phpFilePath->getRealPath());
            $classInfo = $this->getClassReflectionInstance($classNamespace);

            if ($classInfo->getShortName() == $className) {
                $outInfo = $this->mountAnalyzeReturnString($classInfo);
            }
        }

        return $outInfo;
    }


    /**
     * Method witch find all PHP files in passed directory
     *
     * @param string $projectSrcPath Working directory path
     * @return Finder Finder object with data from finded files
     */
    private function getFinder(string $projectSrcPath): Finder
    {
        return Finder::create()
            ->in($projectSrcPath)
            ->name('/^[A-Z]+\.php$/')
            ;
    }


    /**
     * Method create Reflectiop class instance
     *
     * @param string $classNamespace Name of class for create instant
     * @return \ReflectionClass|string Reflection object with data about passed class
     */
    private function getClassReflectionInstance(string $classNamespace)
    {
        try {
            return new \ReflectionClass($classNamespace);
        } catch (\ReflectionException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Method determines the type of the passed class
     *
     * @param object $classInfo The class the type of which you want to determine
     * @return string Returnable class type
     */
    private function getClassType(object $classInfo) : string
    {
        if($classInfo->isAbstract()) return 'Abstract';
        if($classInfo->isFinal()) return 'Final';
        return 'Normal';
    }

    /**
     * Method witch create array with list of class methods grouped by type
     *
     * @param object $classInfo The class the type of which you want to determine
     * @return array Returnable array of grouped methods
     */
    private function getClassMethods(object $classInfo) : array
    {
          return [
              'private' => $classInfo->getMethods(\ReflectionMethod::IS_PRIVATE),
              'public' => $classInfo->getMethods(\ReflectionMethod::IS_PUBLIC),
              'protected' => $classInfo->getMethods(\ReflectionMethod::IS_PROTECTED)
          ];
    }

    /**
     * Method witch create array with list of class properties grouped by type
     *
     * @param object $classInfo The class the type of which you want to determine
     * @return array Returnable array of grouped methods
     */
    private function getClassProperties(object $classInfo) : array
    {
        return [
            'private' => $classInfo->getProperties(\ReflectionProperty::IS_PRIVATE),
            'public' => $classInfo->getProperties(\ReflectionProperty::IS_PUBLIC),
            'protected' => $classInfo->getProperties(\ReflectionProperty::IS_PROTECTED)
        ];
    }

    /**
     * Method create resulting string with info about class for output
     *
     * @param $info /Reflection instance of passed class
     * @return string Resulting string with class info
     */
    private function mountAnalyzeReturnString($info) : string
    {
        $properties = $this->getClassProperties($info);
        $methods = $this->getClassMethods($info);
        $hasConstructor = (!is_null($info->getConstructor()) ? ' (1 of them is constructor)' : '');

        return "Class: {$info->getShortName()} is {$this->getClassType($info)} " . \PHP_EOL . "
                Properties: " . \PHP_EOL . "
                    public: ". count($properties['public']) . \PHP_EOL . "
                    protected: ". count($properties['protected']) . \PHP_EOL . "
                    private: ". count($properties['private']) . \PHP_EOL . "
                Methods:
                    public: ". count($methods['public']) . $hasConstructor . \PHP_EOL  . "
                    protected: ". count($methods['protected']) . \PHP_EOL . "
                    private: ". count($methods['private']) . \PHP_EOL;
    }

}