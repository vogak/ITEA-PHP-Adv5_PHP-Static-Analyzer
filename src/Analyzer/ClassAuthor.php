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

use Greeflas\StaticAnalyzer\ClassInfoHelper;
use phpDocumentor\Reflection\DocBlockFactory;
use Symfony\Component\Finder\Finder;

/**
 * Analyzer that provides number of classes, interfaces and trait created by needed developer.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
final class ClassAuthor
{
    private $projectDir;
    private $developerEmail;

    public function __construct(string $projectDir, string $developerEmail)
    {
        $this->projectDir = $projectDir;
        $this->developerEmail = $developerEmail;
    }

    public function analyze(): int
    {
        /* @var \Symfony\Component\Finder\SplFileInfo[] $finder */
        $finder = Finder::create()
            ->in($this->projectDir)
            ->files()
            ->name('/^[A-Z].+\.php$/')
        ;

        $docBlockFactory =  DocBlockFactory::createInstance();

        $counter = 0;

        foreach ($finder as $file) {
            $namespace = ClassInfoHelper::getFullClassNameFromFile($file->getPathname());

            try {
                $reflector = new \ReflectionClass($namespace);
            } catch (\ReflectionException $e) {
                continue;
            }

            if (!$docComment = $reflector->getDocComment()) {
                continue;
            }

            $docBlock = $docBlockFactory->create($docComment);

            /* @var \phpDocumentor\Reflection\DocBlock\Tags\Author[] $authors */
            $authors = $docBlock->getTagsByName('author');

            foreach ($authors as $author) {
                if ($author->getEmail() === $this->developerEmail) {
                    ++$counter;
                }
            }
        }

        return $counter;
    }
}
