<?php
$config = new \Config\Database();
$db = $config->connect();

// Test direct insert
$data = [
    'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
    'user_id' => 1,
    'judul' => '',
    'status' => 'draft',
    'current_step' => 1,
    'step_1_data' => json_encode([]),
    'step_2_data' => json_encode([]),
    'step_3_data' => json_encode([]),
    'step_4_data' => json_encode([]),
    'step_5_data' => json_encode([]),
];

$builder = $db->table('proposal_pengajuan');
$result = $builder->insert($data);

if ($result) {
    echo "✓ Proposal created successfully\n";
    echo "UUID: " . $data['uuid'] . "\n";
} else {
    echo "✗ Insert failed\n";
    echo "Last Error: " . $db->error()['message'] . "\n";
}
