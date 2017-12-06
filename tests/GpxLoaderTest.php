<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\GpxLoader;

class GpxLoaderTest extends TestCase
{
    /**
     * @expectedException \App\Exception\LoaderException
     */
    public function test_invalid()
    {
        GpxLoader::loadFromFile(__DIR__.'/fixtures/invalid.gpx');
    }

    public function test_simpleTrek()
    {
        $gpxFile = GpxLoader::loadFromFile(__DIR__.'/fixtures/short_trek.gpx');
        $this->assertCount(1, $gpxFile->tracks);
    }
}
