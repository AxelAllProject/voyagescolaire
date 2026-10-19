<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Les pages ne dépendent pas des assets compilés pendant les tests.
        $this->withoutVite();
    }
}
