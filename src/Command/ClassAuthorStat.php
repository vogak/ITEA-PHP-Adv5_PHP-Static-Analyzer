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

use Greeflas\StaticAnalyzer\Analyzer\ClassAuthor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for getting information about how many classes/interfaces/traits
 * was created by some developer.
 *
 * Example of usage
 * ./bin/console stat:class-author vldmr.kuprienko@gmail.com $PWD/src
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ClassAuthorStat extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stat:class-author')
            ->setDescription('Shows statistic about classes/interfaces/traits authors')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'E-mail address of needed developer.'
            )
            ->addArgument(
                'project-src',
                InputArgument::REQUIRED,
                'Absolute path to project source code.'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $projectSrc = $input->getArgument('project-src');

        $analyzer = new ClassAuthor($projectSrc, $email);
        $count = $analyzer->analyze();

        $output->writeln(\sprintf(
            '<info>Developer %s created %d of classes/interfaces/traits</info>',
            $email,
            $count
        ));
    }
}
