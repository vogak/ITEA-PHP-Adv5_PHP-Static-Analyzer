<?php

/*
 * This file is part of the "PHP Static Analyzer" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Analyzer;

use Greeflas\StaticAnalyzer\ClassInfoHelperCountPropertiesAndMethod;
use Greeflas\StaticAnalyzer\ClassInfoHelperTypeClass;
use Symfony\Component\Finder\Finder;

/**
 * Class ClassAnalysis
 *
 * An analyzer that provides the number of properties and methods in a given PHP class.
 *
 *
 * @author Pavel Peregin <pereginp@gmail.com>
 */
final class ClassAnalysis
{
    private $projectDir;
    private $nameClass;

    public function __construct(string $projectDir, string $nameClass)
    {
        $this->projectDir = $projectDir;
        $this->nameClass = $nameClass;
    }

    /**
     * @return string
     */
    public function analysisType(): string
    {
        /* @var \Symfony\Component\Finder\SplFileInfo[] $finder */
        $finder = Finder::create()
            ->in($this->projectDir)
            ->files()
            ->name($this->nameClass . '.php')
        ;

        $typeClass = null;

        foreach ($finder as $file) {
            $typeClass = ClassInfoHelperTypeClass::getTypeClassFromFile($file->getPathname());
        }

        return $typeClass;
    }

    /**
     * @return array
     */
    public function analysisCount(): array
    {
        /* @var \Symfony\Component\Finder\SplFileInfo[] $finder */
        $finder = Finder::create()
            ->in($this->projectDir)
            ->files()
            ->name($this->nameClass . '.php')
        ;

        $count = null;

        foreach ($finder as $file) {
            $count = ClassInfoHelperCountPropertiesAndMethod::getCountMethodFromFile($file->getPathname());
        }

        return $count;
    }
}
