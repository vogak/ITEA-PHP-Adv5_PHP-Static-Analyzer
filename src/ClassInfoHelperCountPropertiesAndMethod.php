<?php

/*
 * This file is part of the "PHP Static Analyzer" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer;

use \Greeflas\StaticAnalyzer\ClassDataCount;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Helper class for getting the number of properties and methods in PHP classes.
 *
 * @author Pavel Peregin <pereginp@gmail.com>
 */
final class ClassInfoHelperCountPropertiesAndMethod
{
    /**
     * Gets number of properties and methods in PHP class.
     *
     * @param string $filePath Path to PHP file with class.
     *
     * @return array
     */
    public static function getCountMethodFromFile(string $filePath): ClassDataCount
    {
        $contents = \file_get_contents($filePath);

        $count = new ClassDataCount();
        $gettingClass = $getMethodOrProperties = false;
        $getPublic = $getProtected = $getPrivate = false;

        foreach (\token_get_all($contents) as $token) {
            $hasTokenInfo = \is_array($token);

            if ($hasTokenInfo && \T_CLASS == $token[0]) {
                $gettingClass = true;
            }

            if ($gettingClass) {
                if ($hasTokenInfo && \T_PUBLIC == $token[0]) {
                    $getMethodOrProperties = true;
                    $getPublic = true;
                } elseif ($hasTokenInfo && \T_PROTECTED == $token[0]) {
                    $getMethodOrProperties = true;
                    $getProtected = true;
                } elseif ($hasTokenInfo && \T_PRIVATE == $token[0]) {
                    $getMethodOrProperties = true;
                    $getPrivate = true;
                }

                if ($getMethodOrProperties) {
                    if (';' === $token) {
                        if ($getPublic) {
                            $count->propPublic++;
                            $getMethodOrProperties = false;
                            $getPublic = false;
                        } elseif ($getProtected) {
                            $count->propProtected++;
                            $getMethodOrProperties = false;
                            $getProtected = false;
                        } elseif ($getPrivate) {
                            $count->propPrivate++;
                            $getMethodOrProperties = false;
                            $getPrivate = false;
                        }
                    } elseif ($hasTokenInfo && \T_FUNCTION == $token[0]) {
                        if ($getPublic) {
                            $count->funcPublic++;
                            $getMethodOrProperties = false;
                            $getPublic = false;
                        } elseif ($getProtected) {
                            $count->funcProtected++;
                            $getMethodOrProperties = false;
                            $getProtected = false;
                        } elseif ($getPrivate) {
                            $count->funcPrivate++;
                            $getMethodOrProperties = false;
                            $getPrivate = false;
                        }
                    }
                }
            }
        }

        return $count;
    }
}
