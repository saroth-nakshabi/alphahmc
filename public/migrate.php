<?php

if (isset($_POST['submit'])) {
    // Secret key for basic security check
    $secret_key = 'alpha@migration';
    $token = $_POST['token'] ?? null;

    // Check for token and validate it
    if ($token !== $secret_key) {
        echo 'Access denied';
        die();
    }

    // Load Laravel framework
    require __DIR__ . '/../vendor/autoload.php'; // Correct path for autoload.php
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    try {
        // Create an instance of the console kernel
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

        // Run migrations with --force to bypass confirmation prompts
        $kernel->call('migrate', ['--force' => true]);

        echo "Migrations ran successfully.";
    } catch (Exception $e) {
        echo "Migration failed: " . $e->getMessage();
    }

    // Optional: Remove this script after migration
    // unlink(__FILE__);
}
?>

<h1>Alpha Education Migration Request</h1>
<form method="POST" action="">
    <input type="password" name="token" placeholder="Enter secret token">
    <button type="submit" name="submit">Run Migrations</button>
</form>