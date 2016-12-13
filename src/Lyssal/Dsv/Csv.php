<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Dsv;

/**
 * Class to read and write CSV (comma-separated values) files.
 */
class Csv extends Dsv
{
    /**
     * @var string Mime-type
     */
    const MIME_TYPE = 'text/csv';
}
