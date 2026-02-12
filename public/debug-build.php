<?php
echo "Build files check:\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Public path: " . public_path() . "\n";
echo "Build directory exists: " . (is_dir(public_path('build')) ? 'YES' : 'NO') . "\n";
echo "Manifest exists: " . (file_exists(public_path('build/manifest.json')) ? 'YES' : 'NO') . "\n";
echo "Build directory contents:\n";
if (is_dir(public_path('build'))) {
    $files = scandir(public_path('build'));
    foreach($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- $file\n";
        }
    }
} else {
    echo "Build directory not found!\n";
}

echo "\nEnvironment Info:\n";
echo "APP_ENV: " . env('APP_ENV') . "\n";
echo "APP_DEBUG: " . env('APP_DEBUG') . "\n";
echo "Server Document Root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' . "\n";
?>