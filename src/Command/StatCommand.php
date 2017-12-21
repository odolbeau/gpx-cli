<?php

declare(strict_types=1);

namespace App\Command;

use App\GpxLoader;
use App\GPX\DuplicatePointsFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StatCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('stat')

            // the short description shown while running "php bin/console list"
            ->setDescription('Display statistics of a GPX file.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Display statistics of a GPX file.')

            ->addArgument('file', InputArgument::REQUIRED, 'The GPX file to analyze.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('GPX stat viewer');

        $file = $input->getArgument('file');
        $listing = [];

        $gpxFile = GpxLoader::loadFromFile($file);

        $listing[] = sprintf('Your GPX file contains <info>%d</info> tracks', count($gpxFile->tracks));

        $io->listing($listing);

        if (1 < $count = count($gpxFile->tracks)) {
            $io->note([
                "As there is more than 1 track ($count) in your GPX file, you should consider to split it. Use:",
                "./console split $file /tmp/gpx/",
            ]);
        }

        foreach ($gpxFile->tracks as $index => $track) {
            $finder = new DuplicatePointsFinder($track->getPoints());

            $trackPoints = $track->getPoints();
            $duplicatePoints = $finder->findDuplicates($track->getPoints());

            $io->section("Track #$index");
            $io->text([
                sprintf('This track contains <info>%d</info> segments and <info>%d</info> points.', count($track->segments), count($trackPoints)),
                sprintf('This track contains <info>%d</info> duplicated points.', count($duplicatePoints)),
            ]);

            if (0 < $count = count($duplicatePoints)) {
                $io->note([
                    "As there is $count duplicate points in your track, you should consider to clean it. Use:",
                    "./console remove-duplicate-points $file /tmp/gpx/",
                ]);
            }
        }
    }
}
