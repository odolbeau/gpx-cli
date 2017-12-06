<?php

declare(strict_types=1);

namespace App;

use App\Exception\LoaderException;
use phpGPX\phpGPX;

class GpxLoader
{
    /**
     * Creates a new loader.
     *
     * @param string $file The GPX file to load
     *
     * @throws LoaderException If the file does not exist or is not readable
     */
    public static function loadFromFile(string $file)
    {
        if (!is_file($file)) {
            throw new LoaderException(sprintf('The file "%s" does not exist', $file));
        }
        if (!is_readable($file)) {
            throw new LoaderException(sprintf('The file "%s" is not readable', $file));
        }
        if (!stream_is_local($file)) {
            throw new LoaderException(sprintf('The file "%s" is not a local file', $file));
        }

        $gpx = new phpGPX();
        try {
            return $gpx->load($file);
        } catch (\Exception $e) {
            throw new LoaderException(sprintf('The file "%s" is not a valid gpx file', $file), 0, $e);
        }
    }
}
