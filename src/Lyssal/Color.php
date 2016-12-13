<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal;

use Lyssal\Exception\LyssalException;

/**
 * Class to manipulate colors.
 */
class Color
{
    /**
     * @var integer Red shade
     */
    protected $red;

    /**
     * @var integer Green shade
     */
    protected $green;

    /**
     * @var integer Blue shade
     */
    protected $blue;


    /**
     * Constructor.
     *
     * @param mixed ...$color The hexadecimal color or the RGB colors if three parameters
     * @throws \Lyssal\Exception\LyssalException If arguments are wrong
     */
    public function __construct(...$color)
    {
        if (1 === count($color)) {
            $this->initHexadecimalColor($color[0]);
        } elseif (3 === count($color)) {
            $this->red = (int) $color[0];
            $this->green = (int) $color[1];
            $this->blue = (int) $color[2];
        } else {
            throw new LyssalException('The Color constructor can have one or three arguments.');
        }
    }


    /**
     * Get the color in hexadecimal.
     *
     * @return string Color
     */
    public function getHexadecimalColor()
    {
        return '#'.strtoupper(str_pad(dechex($this->red), 2, 0, STR_PAD_LEFT).str_pad(dechex($this->green), 2, 0, STR_PAD_LEFT).str_pad(dechex($this->blue), 2, 0, STR_PAD_LEFT));
    }

    /**
     * Return the red.
     * @return int Red
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * Return the green.
     * @return int Green
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * Return the blue.
     * @return int Blue
     */
    public function getBlue()
    {
        return $this->blue;
    }


    /**
     * Initialise the color with its hexadecimal code.
     *
     * @param string $hexadecimalColor Color in hexadecimal
     * @throws \Lyssal\Exception\LyssalException If the hexadecimal code is incorrect
     */
    protected function initHexadecimalColor($hexadecimalColor)
    {
        if (0 !== strpos($hexadecimalColor, '#')) {
            throw new LyssalException('The hexadecimal color must start with "#".');
        }
        $hexadecimalColor = substr($hexadecimalColor, 1);

        if (3 === strlen($hexadecimalColor)) {
            $hexadecimalColor = str_repeat(substr($hexadecimalColor, 0, 1), 2).str_repeat(substr($hexadecimalColor, 1, 1), 2).str_repeat(substr($hexadecimalColor, 2, 1), 2);
        } else if (strlen($hexadecimalColor) != 6) {
            throw new LyssalException('The hexadecimal color code "'.$hexadecimalColor.'" is incorrect.');
        }

        list($red, $green, $blue) = str_split($hexadecimalColor, 2);
        $this->red = hexdec($red);
        $this->green = hexdec($green);
        $this->blue = hexdec($blue);
    }


    /**
     * Lighten the color.
     *
     * @param int $percent Light percent
     * @return \Lyssal\Color Color
     */
    public function lighten($percent)
    {
        return $this->adjustBrightness($percent);
    }

    /**
     * Darken the color.
     *
     * @param int $percent Dark percent
     * @return \Lyssal\Color Color
     */
    public function darken($percent)
    {
        return $this->adjustBrightness(- $percent);
    }

    /**
     * Adjust the color brightness.
     *
     * @param int $signedPercent Signed percentage (from -100 to 100)
     * @return \Lyssal\Color Color
     */
    protected function adjustBrightness($signedPercent)
    {
        $this->red = max(0, min(255, $this->red + 2.55 * $signedPercent));
        $this->green = max(0, min(255, $this->green + 2.55 * $signedPercent));
        $this->blue = max(0, min(255, $this->blue + 2.55 * $signedPercent));

        return $this;
    }
}
