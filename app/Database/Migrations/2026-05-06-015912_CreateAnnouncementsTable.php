<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'            => ['type' => 'VARCHAR', 'constraint' => 36],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'content'         => ['type' => 'TEXT'],
            'image'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_attachment' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'view_count'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'       => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('uuid');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('announcements');
    }

    public function down()
    {
        $this->forge->dropTable('announcements');
    }
}
