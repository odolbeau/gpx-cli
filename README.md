# GPX cli tool

[![Build Status](https://travis-ci.org/odolbeau/gpx-cli.png)](https://travis-ci.org/odolbeau/gpx-cli)
[![Latest Stable Version](https://poser.pugx.org/bab/gpx-cli/v/stable.svg)](https://packagist.org/packages/bab/gpx-cli)
[![Latest Unstable Version](https://poser.pugx.org/bab/gpx-cli/v/unstable.svg)](https://packagist.org/packages/bab/gpx-cli)

GPX cli is a PHP cli tool to deal with GPX files.

## Installation

    composer create-project bab/gpx-cli

## Usage

Right now, you can display some stats regarding a given GPX file:

    ./console stat my/gpx/file

If needed, you can also split a gpx file containing several tracks into several gpx files

    ./console split my/gpx/file path/to/output/

You can also clean a file by removing all duplicated points

    ./console remove-duplicate-points my/gpx/file path/to/output/

## License

This tool is released under the MIT License. See the bundled LICENSE file for details.
