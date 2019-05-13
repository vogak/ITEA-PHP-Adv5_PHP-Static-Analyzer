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

/**
 * Helper class for getting information about types of PHP classes.
 *
 * @author Pavel Peregin <pereginp@gmail.com>
 */
final class ClassInfoHelperTypeClass
{
    /**
     * Class should not be instantiated.
     */
    public function __construct()
    {
    }

    /**
     * Gets type of class from PHP file.
     *
     * @param string $filePath Path to PHP file with class.
     *
     * @return string
     */
    public static function getTypeClassFromFile(string $filePath): string
    {
        $contents = \file_get_contents($filePath);

        $type = 'normal';

        foreach (\token_get_all($contents) as $token) {
            $hasTokenInfo = \is_array($token);

            if ($hasTokenInfo && \T_FINAL == $token[0]) {
                $type = 'final';
            } elseif ($hasTokenInfo && \T_ABSTRACT == $token[0]) {
                $type = 'abstract';
            } elseif ($hasTokenInfo && \T_CLASS == $token[0]) {
                break;
            }
        }

        return $type;
    }
}
