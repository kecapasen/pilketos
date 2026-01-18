<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModel extends Model
{
    protected $table            = 'votes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $createdField     = 'voted_at';
    protected $updatedField     = '';
    protected $allowedFields    = [
        'voter_id',
        'candidate_id',
        'ip_address'
    ];
    public function getQuickCount()
    {
        return $this->db->table('candidates')
            ->select('candidates.id, candidates.order_number, candidates.chairman_name, candidates.image, COUNT(votes.id) as total_votes')
            ->join('votes', 'votes.candidate_id = candidates.id', 'left') 
            ->groupBy('candidates.id')
            ->orderBy('candidates.order_number', 'ASC')
            ->get()
            ->getResult();
    }
    public function hasVoted($voterId)
    {
        return $this->where('voter_id', $voterId)->countAllResults() > 0;
    }
}