<?php

declare(strict_types=1);

namespace App\Command;

use App\GpxLoader;
use App\GPX\Merger;
use phpGPX\phpGPX;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MergeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('merge')
            ->setDescription('Merge 2 GPX files into 1.')
            ->setHelp('Merge 2 GPX files which contain 1 track into 1 GPX file which contain 1 track.')
            ->addArgument('file1', InputArgument::REQUIRED, 'The first GPX file.')
            ->addArgument('file2', InputArgument::REQUIRED, 'The second GPX file.')
            ->addArgument('output', InputArgument::REQUIRED, 'The name of the GPX file to generate.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('GPX file merger.');

        $file1 = $input->getArgument('file1');
        $file2 = $input->getArgument('file2');
        $output = $input->getArgument('output');

        $gpxFile1 = GpxLoader::loadFromFile($file1);
        if (1 < count($gpxFile1->tracks)) {
            $io->error('Your first GPX file contains more than 1 track. Abort merging.');

            return 1;
        }
        $gpxFile2 = GpxLoader::loadFromFile($file2);
        if (1 < count($gpxFile2->tracks)) {
            $io->error('Your second GPX file contains more than 1 track. Abort merging.');

            return 1;
        }

        $merger = new Merger($gpxFile1, $gpxFile2);
        $file = $merger->merge();
        $file->save($output, phpGPX::XML_FORMAT);
    }
}
