<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Dsv;

/**
 * Class to read and write TSV (tab-separated values) files.
 */
class Tsv extends Dsv
{
    /**
     * @var string Mime-type
     */
    const MIME_TYPE = 'text/tab-separated-values';


    /**
     * {@inheritDoc}
     *
     * @param \Lyssal\File\File|string $file The file
     * @param string      $delimiter The delimiter
     * @param string|null $enclosure The enclosure
     */
    public function __construct($file, $delimiter = "\t", $enclosure = '"')
    {
        parent::__construct($file, $delimiter, $enclosure);
    }
}
