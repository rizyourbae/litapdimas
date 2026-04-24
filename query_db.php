<?php
require 'vendor/autoload.php';
$app = \Config\Services::codeigniter();
$app->initialize();
$group = 'default';
$db = \Config\Database::connect($group);

echo "---ROLES---\n";
$roles = $db->table('roles')->get()->getResultArray();
echo json_encode($roles, JSON_PRETTY_PRINT) . "\n";

echo "---PERMISSIONS---\n";
$perms = $db->table('permissions')->get()->getResultArray();
echo json_encode(array_column($perms, 'name'), JSON_PRETTY_PRINT) . "\n";

echo "---ROLE_PERMISSIONS---\n";
$rp = $db->table('role_permissions')
    ->join('roles','roles.id=role_permissions.role_id')
    ->join('permissions','permissions.id=role_permissions.permission_id')
    ->select('roles.name as role, permissions.name as permission')
    ->get()->getResultArray();
echo json_encode($rp, JSON_PRETTY_PRINT) . "\n";
