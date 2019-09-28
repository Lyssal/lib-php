<?php
use Lyssal\File\File;
use PHPUnit\Framework\TestCase;

/**
 * Test de File.
 */
class FileTest extends TestCase
{
    /**
     * Test File.
     */
    public function testFile()
    {
        File::setDefaultSeparator('_');
        $file = new File(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test.txt');

        $this->assertEquals($file->getFilename(), 'test.txt');
        $this->assertTrue($file->hasExtension());
        $this->assertEquals($file->getExtension(), 'txt');
        $this->assertEquals($file->getFilenameWithoutExtension(), 'test');
        $this->assertTrue($file->exists());
        $this->assertEquals($file->getSize(), 6);
        $this->assertFalse($file->isUrl());
        $this->assertEquals($file->getContent(), 'Coucou');

        $file2 = $file->copy(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test', true);
        $this->assertEquals($file2->getFilenameWithoutExtension(), 'test');
        $this->assertFalse($file2->hasExtension());
        $this->assertEquals($file2->getSize(), 6);
        $file2->setContent('Au revoir');

        $file3 = new File(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test');
        $this->assertTrue($file3->exists());
        $this->assertEquals($file3->getSize(), 9);

        $file3->move(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test.txt', false);
        $this->assertEquals($file3->getFilename(), 'test_1.txt');
        $file3->minify('C\'est un fichier !', 'm', true, 12);
        $this->assertTrue($file3->exists());
        $this->assertEquals($file3->getFilename(), 'cmestmun.txt');
        $file3->delete();
        $this->assertFalse($file3->exists());
    }
}
