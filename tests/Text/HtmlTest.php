<?php
use Lyssal\Text\Html;
use PHPUnit\Framework\TestCase;

/**
 * Test de Html.
 */
class HtmlTest extends TestCase
{
    /**
     * Test deleteTags().
     */
    public function testDeleteTags()
    {
        $htmlTest = '<p>Nous avons déjà esquissé cette <strong>petite <em>figure</em> sombre</strong>. <strong>Cosette</strong> était <em>maigre</em> et <em>blême</em>.</p>';

        $html = new Html($htmlTest);
        $html->deleteTags(['p']);
        $this->assertEquals($html->getText(), '');

        $html = new Html($htmlTest);
        $html->deleteTags(['strong']);
        $this->assertEquals($html->getText(), '<p>Nous avons déjà esquissé cette .  était <em>maigre</em> et <em>blême</em>.</p>');

        $html = new Html($htmlTest);
        $html->deleteTags(['strong', 'em']);
        $this->assertEquals($html->getText(), '<p>Nous avons déjà esquissé cette .  était  et .</p>');
    }

    /**
     * Test makeClickableLinks().
     */
    public function testMakeClickableLinks()
    {
        $html = new Html('<div> http://www.google.fr </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://www.google.fr">http://www.google.fr</a> </div>');

        $html = new Html('<div> www.google.fr/ </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://www.google.fr/">www.google.fr/</a> </div>');

        $html = new Html('<div> google.fr </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://google.fr">google.fr</a> </div>');

        $html = new Html('<div> ww.google.fr </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://ww.google.fr">ww.google.fr</a> </div>');

        $html = new Html('<div> https://ww.google.fr </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="https://ww.google.fr">https://ww.google.fr</a> </div>');

        $html = new Html('<div> <a href="http://www.google.fr">Google</a> </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://www.google.fr">Google</a> </div>');

        $html = new Html('<div> <a href="https://www.google.fr/">Google</a> </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="https://www.google.fr/">Google</a> </div>');

        $html = new Html('<div> www.google.fr/images </div>');
        $html->makeClickableLinks();
        $this->assertEquals($html->getText(), '<div> <a href="http://www.google.fr/images">www.google.fr/images</a> </div>');
    }

    /**
     * Test makeClickableEmails().
     */
    public function testMakeClickableEmails()
    {
        $html = new Html('<div> toto@exemple.fr </div>');
        $html->makeClickableEmails();
        $this->assertEquals($html->getText(), '<div> <a href="mailto:toto@exemple.fr">toto@exemple.fr</a> </div>');

        $html = new Html('<div> <a href="mailto:toto@exemple.fr">Toto</a> </div>');
        $html->makeClickableEmails();
        $this->assertEquals($html->getText(), '<div> <a href="mailto:toto@exemple.fr">Toto</a> </div>');

        $html = new Html('<div> <a href="mailto:toto@exemple.fr">toto@exemple.fr</a> </div>');
        $html->makeClickableEmails();
        $this->assertEquals($html->getText(), '<div> <a href="mailto:toto@exemple.fr">toto@exemple.fr</a> </div>');
    }

    /**
     * Test makeClickableEmails().
     */
    public function testAddTargetBlankToLinks()
    {
        $html = new Html('<a>Test</a>');
        $html->addTargetBlankToLinks();
        $this->assertEquals($html->getText(), '<a>Test</a>');

        $html = new Html('<a href="http://www.lyssal.com/">Test</a>');
        $html->addTargetBlankToLinks();
        $this->assertEquals($html->getText(), '<a href="http://www.lyssal.com/" target="_blank">Test</a>');
    }
}
