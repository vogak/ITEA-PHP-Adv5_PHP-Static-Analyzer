<?php

namespace Greeflas\StaticAnalyzer;

/**
 * Helper class for getting information about PHP classes.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
final class ClassInfoHelper
{
    /**
     * Class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Gets full class name from PHP file.
     *
     * @param string $filePath Path to PHP file with class.
     *
     * @return string
     */
    public static function getFullClassNameFromFile(string $filePath): string
    {
        $contents = \file_get_contents($filePath);

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
