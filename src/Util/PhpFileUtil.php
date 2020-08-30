<?php

declare(strict_types=1);

namespace ITEA\PhpStaticAnalyzer\Util;

final class PhpFileUtil
{
    public static function getClassNameFromFile(string $pathToFile): string
    {
        $contents = \file_get_contents($pathToFile);

        $namespace = $class = '';
        $gettingNamespace = $gettingClass = false;

        foreach (\token_get_all($contents) as $token) {
            $hasTokenInfo = \is_array($token);

            if ($hasTokenInfo && $token[0] == \T_NAMESPACE) {
                $gettingNamespace = true;
            } elseif ($hasTokenInfo && $token[0] == \T_CLASS) {
                $gettingClass = true;
            }

            if ($gettingNamespace) {
                if($hasTokenInfo && \in_array($token[0], [\T_STRING, \T_NS_SEPARATOR])) {
                    $namespace .= $token[1];
                } elseif (';' === $token) {
                    $gettingNamespace = false;
                }
            } elseif ($gettingClass === true) {
                if($hasTokenInfo && $token[0] == \T_STRING) {
                    $class = $token[1];

                    break;
                }
            }
        }

        return $namespace ? $namespace . '\\' . $class : $class;
    }
}
