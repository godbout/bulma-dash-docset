<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/godbout/dash-docset-builder/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}