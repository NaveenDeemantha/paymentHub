<?php

use App\Models\Transaction;
use App\Services\PaycenterService;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());
Illuminate\Support\Facades\Facade::setFacadeApplication($app);

// Mock the Service just to see what payload it generates?
// Actually, since I modified the Service class directly, I want to see what payload it constructs.
// I can't easily spy on the payload without modifying the mock or class.
// Instead, I'll instantiate the real service (if config allows) or just check the logic math here.
// But better: I will inspect what initiatePayment does by creating a subclass or reflection, 
// OR I check the logs if I run it.

// Let's just run a quick manual calculation check with the class logic to be super sure of PHP float math
$amount = 25000;
$converted = (float) $amount * 100;

echo "Input: $amount\n";
echo "Converted: $converted\n";

if ($converted == 2500000) {
    echo "PASS: Logic is correct for 25000 -> 2500000\n";
} else {
    echo "FAIL: Math logic error.\n";
}
