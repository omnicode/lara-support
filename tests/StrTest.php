<?php

namespace Tests;

use LaraSupport\Str;

class StrTest extends TestCase
{

    /**
     *
     */
    public function testPositions()
    {
        $str = '"I love Php, I love php too!","PHP"';
        $this->assertEquals([1 => 20], Str::positions($str, 'php'));
    }

    /**
     *
     */
    public function testIpositions()
    {
        $positions = [
            1 => 8,
            2 => 20,
            3 => 31
        ];
        $str = '"I love Php, I love php too!","PHP"';
        $this->assertEquals($positions, Str::ipositions($str, 'php'));
    }

    /**
     *
     */
    public function testAfter()
    {
        $str = '"I love Php, I love php too!","PHP"';
        $this->assertEquals(' too!","PHP"', Str::after($str, 'php'));
        $this->assertEquals(' too!","PHP"', Str::after($str, 'php', 1, true));
        $this->assertEquals(', I love php too!","PHP"', Str::after($str, 'php', 1, false));
        $this->assertEquals(' too!","PHP"', Str::after($str, 'php', 2, false));
        $this->assertEquals('"', Str::after($str, 'php', 3, false));
        $this->assertEquals('"', Str::after($str, 'php', Str::LAST, false));
        $this->assertFalse(Str::after($str, 'php', 4));
    }

    /**
     *
     */
    public function testBefore()
    {
        $str = '"I love Php, I love php too!","PHP"';
        $this->assertEquals('"I love Php, I love ', Str::before($str, 'php'));
        $this->assertEquals('"I love Php, I love ', Str::before($str, 'php', 1, true));
        $this->assertEquals('"I love ', Str::before($str, 'php', 1, false));
        $this->assertEquals('"I love Php, I love ', Str::before($str, 'php', 2, false));
        $this->assertEquals('"I love Php, I love php too!","', Str::before($str, 'php', 3, false));
        $this->assertEquals('"I love Php, I love php too!","', Str::before($str, 'php', Str::LAST, false));
        $this->assertFalse(Str::before($str, 'php', 4));
    }

    /**
     *
     */
    public function testBetween()
    {
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testWrap()
    {
        $str = '"I love Php, I love php too!","PHP"';
        $expected = '"I love Php, I love <div>php</div> too!","PHP"';
        $this->assertEquals($expected, Str::wrap($str, 'php', '<div>', '</div>'));
    }

    /**
     *
     */
    public function testIwrap()
    {
        $str = '"I love Php, I love php too!","PHP"';
        $expected = '"I love <div>Php</div>, I love <div>php</div> too!","<div>PHP</div>"';
        $this->assertEquals($expected, Str::iwrap($str, 'php', '<div>', '</div>'));
    }
}
