<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sqlitePath = database_path('database.sqlite');

if (! file_exists($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found: {$sqlitePath}\n");
    exit(1);
}

config([
    'database.connections.sqlite_old' => [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
]);

$tables = [
    'users',
    'assessment_results',
    'assessment_questions',
    'site_contents',
    'navigation_items',
    'social_links',
    'assessment_attempts',
];

$source = DB::connection('sqlite_old');
$target = DB::connection('mysql');

$target->statement('SET FOREIGN_KEY_CHECKS=0');

foreach (array_reverse($tables) as $table) {
    $target->table($table)->truncate();
}

foreach ($tables as $table) {
    $rows = $source->table($table)->get()->map(fn ($row) => (array) $row)->all();

    foreach (array_chunk($rows, 100) as $chunk) {
        if ($chunk !== []) {
            $target->table($table)->insert($chunk);
        }
    }

    echo "{$table}: ".count($rows)." rows migrated\n";
}

$target->statement('SET FOREIGN_KEY_CHECKS=1');

echo "Done.\n";
