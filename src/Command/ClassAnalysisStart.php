<?php

/*
 * This file is part of the "PHP Static Analyzer" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Command;

use Greeflas\StaticAnalyzer\Analyzer\ClassAnalysis;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for getting class anallise
 *
 * Example for usage
 * ./bin/console stat:analyzer-class <full-class-name>
 *
 *
 * @author Pavel Peregin <pereginp@gmail.com>
 */
final class ClassAnalysisStart extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stat:analyzer-class')
            ->setDescription('Show analise of classes')
            ->addArgument(
                'full-class-name',
                InputArgument::REQUIRED,
                'Name of classes of needed developer'
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullCalssName = $input->getArgument('full-class-name');

        $analyzer = new ClassAnalysis($fullCalssName);
        $type = $analyzer->analysisType();
        $count = $analyzer->analysisCount();

        $output->writeln(\sprintf(
            '<info>
                                                 Class: %s is %s
                                                 Properties:
                                                 public: %d
                                                 protected: %d
                                                 private: %d
                                                 Methods:
                                                 public: %d
                                                 protected: %d
                                                 private: %d
                                           </info>',
            $fullCalssName,
            $type,
            $count->propPublic,
            $count->propProtected,
            $count->propPrivate,
            $count->funcPublic,
            $count->funcProtected,
            $count->funcPrivate
            ));
    }
}
