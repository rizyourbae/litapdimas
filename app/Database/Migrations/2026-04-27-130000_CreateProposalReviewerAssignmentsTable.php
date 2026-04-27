<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalReviewerAssignmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'unique' => true,
            ],
            'proposal_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'reviewer_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'assigned_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'assignment_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'assigned',
                'comment' => 'assigned, reviewed, declined',
            ],
            'recommendation' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'pending',
                'comment' => 'pending, recommended, revision, rejected',
            ],
            'review_notes' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->addKey('reviewer_user_id');
        $this->forge->addKey('status');
        $this->forge->addUniqueKey(['proposal_id', 'reviewer_user_id']);
        $this->forge->createTable('proposal_reviewer_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('proposal_reviewer_assignments');
    }
}
