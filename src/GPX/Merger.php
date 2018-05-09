<?php

declare(strict_types=1);

namespace App\GPX;

use phpGPX\Models\GpxFile;
use phpGPX\Models\Track;

class Merger
{
    private $file1;
    private $file2;

    public function __construct(GpxFile $file1, GpxFile $file2)
    {
        $this->file1 = $file1;
        $this->file2 = $file2;
    }

    public function merge(): GpxFile
    {
        $track = new Track();
        $track->segments = array_merge($this->file1->tracks[0]->segments, $this->file2->tracks[0]->segments);

        $gpxFile = clone $this->file1;
        $gpxFile->tracks = [$track];

        return $gpxFile;
    }
}
