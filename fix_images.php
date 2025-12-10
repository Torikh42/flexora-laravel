<?php

use App\Models\StudioClass;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting image migration...\n\n";

$classes = StudioClass::all();
$migratedCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($classes as $class) {
    // Skip if already using new format (starts with 'classes/')
    if (str_starts_with($class->image, 'classes/')) {
        echo "✓ Skipping {$class->name}: Already using new format ({$class->image})\n";
        $skippedCount++;
        continue;
    }
    
    // Skip if no image
    if (empty($class->image)) {
        echo "- Skipping {$class->name}: No image\n";
        $skippedCount++;
        continue;
    }
    
    $oldPath = public_path('images/' . $class->image);
    $newRelativePath = 'classes/' . $class->image;
    $newStoragePath = 'classes/' . $class->image;
    
    // Check if old file exists
    if (!file_exists($oldPath)) {
        echo "✗ Error for {$class->name}: File not found at {$oldPath}\n";
        $errorCount++;
        continue;
    }
    
    try {
        // Copy file to new location
        $fileContents = file_get_contents($oldPath);
        Storage::disk('public')->put($newStoragePath, $fileContents);
        
        // Update database record
        $class->image = $newRelativePath;
        $class->save();
        
        echo "✓ Migrated {$class->name}: {$class->image}\n";
        $migratedCount++;
        
    } catch (\Exception $e) {
        echo "✗ Error migrating {$class->name}: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n--- Migration Summary ---\n";
echo "Migrated: {$migratedCount}\n";
echo "Skipped: {$skippedCount}\n";
echo "Errors: {$errorCount}\n";
echo "Total: " . $classes->count() . "\n";
