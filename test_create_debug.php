<?php
// Test script to debug proposal creation

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/public/index.php';

$db = \Config\Database::connect();

// Check users
echo "=== USERS TABLE ===\n";
$users = $db->table('users')->select('id, username, email')->limit(10)->get()->getResult();
foreach ($users as $user) {
    echo "ID: {$user->id}, Username: {$user->username}, Email: {$user->email}\n";
}

// Try to create a proposal manually
echo "\n=== CREATING PROPOSAL ===\n";

if (empty($users)) {
    echo "ERROR: No users in database!\n";
    exit(1);
}

$userId = $users[0]->id;
echo "Using user ID: $userId\n";

$uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
echo "Generated UUID: $uuid\n";

$proposalData = [
    'uuid' => $uuid,
    'user_id' => $userId,
    'judul' => '',
    'status' => 'draft',
    'current_step' => 1,
    'step_1_data' => json_encode([]),
    'step_2_data' => json_encode([]),
    'step_3_data' => json_encode([]),
    'step_4_data' => json_encode([]),
    'step_5_data' => json_encode([]),
];

$result = $db->table('proposal_pengajuan')->insert($proposalData);

if ($result) {
    echo "✓ Proposal created successfully!\n";
    $created = $db->table('proposal_pengajuan')->where('uuid', $uuid)->first();
    echo "Created proposal ID: " . $created->id . "\n";
} else {
    echo "✗ Insert failed\n";
    echo "DB Error: " . print_r($db->error(), true) . "\n";
}
