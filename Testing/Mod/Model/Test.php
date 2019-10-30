<?php

namespace Test\Mod\Model;

use Testing\Mod\Api\TestingInterface as DataAPI;

class Test implements DataAPI
{
    /**
	 * {@inheritdoc}
	 */
    public function test($test)
    {
        return "Try" . $test;
    }
}