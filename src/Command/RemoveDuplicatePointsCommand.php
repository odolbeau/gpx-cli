<?php

declare(strict_types=1);

namespace App\Command;

use App\GpxLoader;
use App\GPX\DuplicatePointsFinder;
use phpGPX\phpGPX;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveDuplicatePointsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('remove-duplicate-points')
            ->setDescription('Remove all duplicated points in your tracks.')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command will remove all "duplicated" points in your tracks.
If there is several points with the same latitude, longitude & elevation, only the first one will be kept.
EOF
            )
            ->addArgument('file', InputArgument::REQUIRED, 'The GPX file to split.')
            ->addArgument('output', InputArgument::REQUIRED, 'The output directory for splitted files.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Duplicated points remover.');

        $file = $input->getArgument('file');
        $output = $input->getArgument('output');

        $gpxFile = GpxLoader::loadFromFile($file);

        foreach ($gpxFile->tracks as $track) {
            foreach ($track->segments as $segment) {
                $finder = new DuplicatePointsFinder($segment->points);
                $segment->points = $finder->findUniqs();
            }
        }

        $path = sprintf('%s/cleaned.gpx', $output);
        $gpxFile->save($path, phpGPX::XML_FORMAT);
    }
}
