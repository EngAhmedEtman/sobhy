<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@gmail.com')->first();
auth()->login($user);

$roles = \App\Models\Role::withCount(['users' => function($q) {
    $q->where('email', '!=', 'admin@gmail.com');
}])->get();

foreach ($roles as $role) {
    echo "Role: {$role->name}\n";
    echo "Users Count: {$role->users_count}\n";
    echo "Can Delete: " . (auth()->user()->hasPermission('roles.delete') ? 'Yes' : 'No') . "\n";
    echo "Show Button Logic: " . (($role->users_count == 0 && auth()->user()->hasPermission('roles.delete')) ? 'SHOW' : 'HIDE') . "\n";
    echo "------------------------\n";
}
