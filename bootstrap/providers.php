<?php

use PHPUnit\Event\Telemetry\System;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\MailConfigServiceProvider::class,
    Froiden\LaravelInstaller\Providers\LaravelInstallerServiceProvider::class,
    App\Providers\RouteServiceProvider::class
];
