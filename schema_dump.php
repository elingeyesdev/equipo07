<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$tables = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' AND name != 'migrations'");
$sql = '';
foreach ($tables as $t) {
    $sql .= $t->sql . "\n\n";
}
file_put_contents('schema.txt', $sql);
echo "Done\n";
