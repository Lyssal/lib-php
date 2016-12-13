<?php
use Lyssal\Text\Slug;

/**
 * Test de Slug.
 */
class SlugTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test next().
     */
    public function testNext()
    {
        $slug = new Slug('ou-est-javert');
        $slug->next('-');
        $this->assertEquals('ou-est-javert-1', $slug->getText());
        $slug->next('*');
        $this->assertEquals('ou-est-javert-1*1', $slug->getText());
        $slug->next('*');
        $this->assertEquals('ou-est-javert-1*2', $slug->getText());
    }
}
