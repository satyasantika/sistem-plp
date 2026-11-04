<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Uji fitur tidak bergantung pada aset hasil build (public/build/manifest.json).
        $this->withoutVite();
    }
}
