<?php

declare(strict_types=1);

namespace App\GPX;

use phpGPX\Models\GpxFile;

class Splitter
{
    private $originalGpxFile;

    public function __construct(GpxFile $originalGpxFile)
    {
        $this->originalGpxFile = $originalGpxFile;
    }

    public function split(): array
    {
        $gpxFiles = [];

        $trackNumber = 1;
        foreach ($this->originalGpxFile->tracks as $track) {
            $newFile = $this->getNewGPXFile($trackNumber);
            $newFile->tracks[] = clone $track;

            $gpxFiles[] = $newFile;
            ++$trackNumber;
        }

        return $gpxFiles;
    }

    private function getNewGPXFile(int $part): GpxFile
    {
        $gpxFile = clone $this->originalGpxFile;
        $gpxFile->tracks = [];

        return $gpxFile;
    }
}
