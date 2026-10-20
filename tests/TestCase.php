<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Inertia root templates call @vite(), which needs public/build/manifest.json.
        // That file only exists after the frontend team runs a build, so tests stub it out.
        $this->withoutVite();
    }
}
