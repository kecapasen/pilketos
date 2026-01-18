<?php

namespace App\Libraries;

use Pusher\Pusher;
use Config\Pusher as PusherConfig;

class PusherService
{
    protected Pusher $pusher;
    protected PusherConfig $config;

    public function __construct()
    {
        $this->config = config('Pusher');
        
        $options = [
            'cluster' => $this->config->cluster,
            'useTLS'  => true,
        ];
        
        // Fix SSL certificate issue on Windows for local development
        if (ENVIRONMENT === 'development') {
            $options['useTLS'] = false; // Use HTTP instead of HTTPS
        }
        
        $this->pusher = new Pusher(
            $this->config->key,
            $this->config->secret,
            $this->config->appId,
            $options
        );
    }

    /**
     * Trigger an event on a channel
     */
    public function trigger(string $channel, string $event, array $data): void
    {
        $this->pusher->trigger($channel, $event, $data);
    }

    /**
     * Broadcast vote update to dashboard
     */
    public function broadcastVoteUpdate(): void
    {
        $db = \Config\Database::connect();
        
        $totalVoters  = (int) $db->table('voters')->countAll();
        $alreadyVoted = (int) $db->table('voters')->where('status', 'voted')->countAllResults();
        
        // Get vote results
        $voteResults = $db->table('candidates')
            ->select('candidates.id, candidates.order_number, candidates.chairman_name, COUNT(votes.id) as total_votes')
            ->join('votes', 'votes.candidate_id = candidates.id', 'left')
            ->groupBy('candidates.id')
            ->orderBy('candidates.order_number', 'ASC')
            ->get()
            ->getResult();
        
        $results = [];
        foreach ($voteResults as $result) {
            $totalVotes = (int) $result->total_votes;
            $percentage = $alreadyVoted > 0 ? round(($totalVotes / $alreadyVoted) * 100, 1) : 0;
            $results[] = [
                'id'           => $result->id,
                'order_number' => $result->order_number,
                'chairman_name' => $result->chairman_name,
                'total_votes'  => $totalVotes,
                'percentage'   => $percentage,
            ];
        }
        
        // Get recent voters
        $recentVoters = $db->table('voters')
            ->select('voters.name, votes.voted_at')
            ->join('votes', 'votes.voter_id = voters.id')
            ->orderBy('votes.voted_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResult();
        
        $recentFormatted = [];
        foreach ($recentVoters as $voter) {
            $recentFormatted[] = [
                'name'     => $voter->name,
                'voted_at' => date('H:i', strtotime($voter->voted_at)),
            ];
        }
        
        $data = [
            'total_voters'    => $totalVoters,
            'already_voted'   => $alreadyVoted,
            'not_voted'       => $totalVoters - $alreadyVoted,
            'vote_percentage' => $totalVoters > 0 ? round(($alreadyVoted / $totalVoters) * 100, 1) : 0,
            'vote_results'    => $results,
            'recent_voters'   => $recentFormatted,
            'timestamp'       => date('H:i:s'),
        ];
        
        $this->trigger('voting-channel', 'vote-updated', $data);
    }

    /**
     * Get Pusher key for frontend
     */
    public function getKey(): string
    {
        return $this->config->key;
    }

    /**
     * Get Pusher cluster for frontend
     */
    public function getCluster(): string
    {
        return $this->config->cluster;
    }
}
