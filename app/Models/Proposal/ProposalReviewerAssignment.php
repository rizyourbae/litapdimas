<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalReviewerAssignment extends Model
{
    protected $table = 'proposal_reviewer_assignments';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'object';

    protected $useSoftDeletes = true;

    protected $protectFields = true;

    protected $allowedFields = [
        'uuid',
        'proposal_id',
        'reviewer_user_id',
        'assigned_by',
        'assignment_notes',
        'status',
        'recommendation',
        'review_score',
        'review_notes',
        'reviewed_at',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'proposal_id' => 'required|integer',
        'reviewer_user_id' => 'required|integer',
        'status' => 'required|in_list[assigned,reviewed,declined]',
        'recommendation' => 'required|in_list[pending,recommended,revision,rejected]',
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal wajib dipilih.',
            'integer' => 'Proposal tidak valid.',
        ],
        'reviewer_user_id' => [
            'required' => 'Reviewer wajib dipilih.',
            'integer' => 'Reviewer tidak valid.',
        ],
        'status' => [
            'required' => 'Status assignment wajib diisi.',
            'in_list' => 'Status assignment tidak valid.',
        ],
        'recommendation' => [
            'required' => 'Status rekomendasi wajib diisi.',
            'in_list' => 'Status rekomendasi tidak valid.',
        ],
    ];

    public function getActiveByProposal(int $proposalId): array
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    public function findActiveByProposalAndReviewer(int $proposalId, int $reviewerUserId): ?object
    {
        $assignment = $this->where('proposal_id', $proposalId)
            ->where('reviewer_user_id', $reviewerUserId)
            ->first();

        return is_object($assignment) ? $assignment : null;
    }

    public function countActiveByProposal(int $proposalId): int
    {
        return $this->where('proposal_id', $proposalId)->countAllResults();
    }
}
