<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReviewScoreToProposalReviewerAssignmentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'review_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'after' => 'recommendation',
            ],
        ];

        $this->forge->addColumn('proposal_reviewer_assignments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('proposal_reviewer_assignments', 'review_score');
    }
}
