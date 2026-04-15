<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$result = App\Models\Complaint::select('type')
    ->selectRaw("SUM(CASE WHEN status <> 'rejected' THEN 1 ELSE 0 END) as non_rejected")
    ->selectRaw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected")
    ->groupBy('type')
    ->orderBy('type')
    ->get()
    ->toArray();
echo json_encode($result);
