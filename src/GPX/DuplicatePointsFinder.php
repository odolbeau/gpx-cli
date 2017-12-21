<?php

declare(strict_types=1);

namespace App\GPX;

class DuplicatePointsFinder
{
    private $points = [];
    private $uniqPoints = [];
    private $duplicatePoints = [];
    private $isProcessed = false;

    public function __construct(array $points)
    {
        $this->points = $points;
    }

    public function findDuplicates(): array
    {
        if (!$this->isProcessed) {
            $this->process();
        }

        return $this->duplicatePoints;
    }

    public function findUniqs(): array
    {
        if (!$this->isProcessed) {
            $this->process();
        }

        return array_values($this->uniqPoints);
    }

    private function process()
    {
        foreach ($this->points as $point) {
            $key = sprintf('%f-%f-%f', $point->latitude, $point->longitude, $point->elevation);

            if (!array_key_exists($key, $this->uniqPoints)) {
                $this->uniqPoints[$key] = $point;
            } else {
                $this->duplicatePoints[] = $point;
            }
        }

        $this->isProcessed = true;
    }
}
