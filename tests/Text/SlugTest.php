<?php
use Lyssal\Text\Slug;
use PHPUnit\Framework\TestCase;

/**
 * Test de Slug.
 */
class SlugTest extends TestCase
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

        $slug = new Slug('ou-est-4-javert');
        $slug->next('-');
        $this->assertEquals('ou-est-4-javert-1', $slug->getText());
        $slug->next('-');
        $this->assertEquals('ou-est-4-javert-2', $slug->getText());
    }
}
