<?php

declare(strict_types=1);

namespace App\Command;

use App\GpxLoader;
use App\GPX\Splitter;
use phpGPX\phpGPX;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SplitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('split')
            ->setDescription('Split a file which contains several tracks into multiple files.')
            ->setHelp('Split a file which contains several tracks into multiple files.')
            ->addArgument('file', InputArgument::REQUIRED, 'The GPX file to split.')
            ->addArgument('output', InputArgument::REQUIRED, 'The output directory for splitted files.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('GPX file splitter.');

        $file = $input->getArgument('file');
        $output = $input->getArgument('output');

        $gpxFile = GpxLoader::loadFromFile($file);

        if (1 === count($gpxFile->tracks)) {
            $io->error('Your GPX file only contains 1 track. Nothing to split.');

            return 1;
        }

        $splitter = new Splitter($gpxFile);
        $splittedFiles = $splitter->split();

        foreach ($splittedFiles as $index => $splittedFile) {
            $path = sprintf('%s/part%d.gpx', $output, ++$index);
            $splittedFile->save($path, phpGPX::XML_FORMAT);
        }
    }
}
