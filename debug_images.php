<?php

use App\Models\StudioClass;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$classes = StudioClass::all();

foreach ($classes as $class) {
    echo "ID: " . $class->id . " | Name: " . $class->name . " | Image: '" . $class->image . "'\n";
}
