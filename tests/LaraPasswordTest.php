<?php

namespace Tests;

use LaraSupport\LaraPassword;

class LaraPasswordTest extends TestCase
{

    /**
     * @var
     */
    protected $laraPassword;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->laraPassword = new LaraPassword();
    }

    /**
     *
     */
    public function testHashPassword()
    {
        //TODO
        $this->assertTrue(true);
    }


    /**
     *
     */
    public function verifyPassword()
    {
        //TODO
        $this->assertTrue(true);
    }

}
