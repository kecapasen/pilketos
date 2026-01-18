<?php

namespace App\Models;

use CodeIgniter\Model;

class VoterModel extends Model
{
    protected $table            = 'voters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $allowedFields    = [
        'token',
        'name',
        'class_group',
        'status'
    ];
    public function getVoterByToken($token)
    {
        return $this->where('token', $token)->first();
    }
    public function markAsVoted($voterId)
    {
        return $this->update($voterId, ['status' => 'voted']);
    }
    public function resetVoterStatus($voterId)
    {
        // Delete the vote record from votes table first
        $db = \Config\Database::connect();
        $db->table('votes')->where('voter_id', $voterId)->delete();
        
        // Then reset voter status
        return $this->update($voterId, ['status' => 'not_voted']);
    }
}