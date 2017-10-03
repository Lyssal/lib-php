<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Dsv;

use Lyssal\Exception\LyssalException;
use Lyssal\File\File;
use Lyssal\Exception\IoException;

/**
 * Class to read and write DSV (delimiter-separated values) files.
 */
class Dsv
{
    /**
     * @var string Mime-type
     */
    const MIME_TYPE = 'text/dsv';


    /**
     * @var \Lyssal\File\File The DSV file
     */
    protected $file;

    /**
     * @var string The delimiter
     */
    protected $delimiter;

    /**
     * @var string Enclosure
     */
    protected $enclosure;

    /**
     * @var array[string] The header
     */
    protected $header = [];

    /**
     * @var array[array[string]] The lines
     */
    protected $lines = [];

    /**
     * @var string The source charset
     */
    protected $sourceCharset;

    /**
     * @var string The target charset
     */
    protected $targetCharset;


    /**
     * Constructor.
     *
     * @param \Lyssal\File\File|string $file      The file
     * @param string                   $delimiter The delimiter
     * @param string|null              $enclosure The enclosure
     */
    public function __construct($file, $delimiter = ',', $enclosure = '"')
    {
        if ($file instanceof File) {
            $this->file = $file;
        } else {
            $this->file = new File($file);
        }

        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }


    /**
     * Set delimiter.
     *
     * @param string $delimiter THe delimiter
     * @return \Lyssal\Dsv\Dsv This
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Set enclosure.
     *
     * @param string|null $enclosure The enclosure
     * @return \Lyssal\Dsv\Dsv This
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Set the DSV header.
     *
     * @param array[string] $header The header
     * @return \Lyssal\Dsv\Dsv This
     */
    public function setHeader(array $header)
    {
        $this->header = $this->complyLine($header);
        return $this;
    }

    /**
     * Get the DSV header.
     *
     * @return array[string] The header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Get the DSV lines.
     *
     * @return array[string] The lines
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Add a line to the DSV.
     *
     * @param array[string] $line The new line
     * @return \Lyssal\Dsv\Dsv This
     */
    public function addLine(array $line)
    {
        $this->lines[] = $this->complyLine($line);
        return $this;
    }

    /**
     * Change the charset.
     *
     * @param string $sourceCharset The source charset
     * @param string $targetCharset The target charset
     * @throws \Lyssal\Exception\LyssalException If the source or the target charset is not defined
     */
    public function changeCharset($sourceCharset = null, $targetCharset = null)
    {
        if ((null === $sourceCharset && null !== $targetCharset) || (null !== $sourceCharset && null === $targetCharset)) {
            throw new LyssalException('Both the source and target charsets must be defined.');
        }

        if (null !== $sourceCharset) {
            $this->sourceCharset = $sourceCharset;
        }
        if (null !== $targetCharset) {
            $this->targetCharset = $targetCharset;
        }
    }

    /**
     * Comply the line.
     *
     * @param array[string] $line A DSV line
     * @return array[string] The complied line
     */
    protected function complyLine(array $line)
    {
        return $this->iconvLine($line);
    }

    /**
     * Convert the DSV line with the good charset.
     *
     * @param array[string] $line The DSV line
     * @return array[string] The encoded line
     */
    protected function iconvLine(array $line)
    {
        if (null !== $this->sourceCharset && null !== $this->targetCharset) {
            foreach ($line as $i => $value) {
                $line[$i] = iconv($this->sourceCharset, $this->targetCharset, $value);
            }
        }

        return $line;
    }


    /**
     * Import the file.
     *
     * @param bool $hasHeader If the DSV has a header
     * @throws \Lyssal\Exception\IoException If the file is unreadable
     */
    public function import($hasHeader = false)
    {
        if (($dsv = fopen($this->file->getPathname(), 'r')) !== false) {
            if ($hasHeader && $line = fgetcsv($dsv, null, $this->delimiter, $this->enclosure)) {
                $this->setHeader($line);
            }

            while (($line = fgetcsv($dsv, null, $this->delimiter, $this->enclosure)) !== false) {
                $this->addLine($line);
            }

            fclose($dsv);
        } else {
            throw new IOException('The DSV file "'.$this->file->getPathname().'" is unreadable.');
        }
    }

    /**
     * Download the DSV file.
     */
    public function download()
    {
        header('Content-Type: '.static::MIME_TYPE);
        header('Content-disposition: filename='.$this->file->getFilename());
        $out = fopen('php://output', 'w');

        if (count($this->header) > 0) {
            if (null === $this->enclosure) {
                fwrite($out, implode($this->complyLine($this->header), $this->delimiter)."\n");
            } else {
                fputcsv($out, $this->complyLine($this->header), $this->delimiter, $this->enclosure);
            }
        }

        foreach ($this->lines as $line) {
            if (null === $this->enclosure) {
                fwrite($out, implode($this->complyLine($line), $this->delimiter)."\n");
            } else {
                fputcsv($out, $this->complyLine($line), $this->delimiter, $this->enclosure);
            }
        }

        fclose($out);
    }
}
