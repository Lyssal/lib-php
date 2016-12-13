<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Math\Geometry\Geometry2d;

/**
 * A 2D dimension.
 */
class Dimension
{
    /**
     * @var int Width
     */
    protected $width;

    /**
     * @var int Height
     */
    protected $height;


    /**
     * Constructor.
     *
     * @param int $width  Width
     * @param int $height Height
     */
    public function __construct($width = 0, $height = 0)
    {
        $this->width = $width;
        $this->height = $height;
    }


    /**
     * Get width.
     *
     * @return int width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width.
     *
     * @param int $width width
     * @return \Lyssal\Math\Geometry\Geometry2d\Dimension This
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get height.
     *
     * @return int height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height.
     *
     * @param int $height height
     * @return \Lyssal\Math\Geometry\Geometry2d\Dimension This
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }
}
