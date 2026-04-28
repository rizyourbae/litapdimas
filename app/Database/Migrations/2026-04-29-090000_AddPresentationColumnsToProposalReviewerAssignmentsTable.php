<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPresentationColumnsToProposalReviewerAssignmentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'presentation_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'after' => 'review_score',
            ],
            'presentation_notes' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'review_notes',
            ],
            'presentation_assessment' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'JSON payload for presentation assessment state',
                'after' => 'presentation_notes',
            ],
            'presentation_recommended_budget' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'after' => 'presentation_assessment',
            ],
            'presentation_reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'presentation_recommended_budget',
            ],
        ];

        $this->forge->addColumn('proposal_reviewer_assignments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('proposal_reviewer_assignments', [
            'presentation_score',
            'presentation_notes',
            'presentation_assessment',
            'presentation_recommended_budget',
            'presentation_reviewed_at',
        ]);
    }
}
