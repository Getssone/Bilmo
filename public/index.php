<?php

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';
require_once 'vendor/autoload.php';
require_once '/path/to/Faker/src/autoload.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
