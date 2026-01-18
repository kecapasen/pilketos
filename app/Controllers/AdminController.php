<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\VoteModel;
use App\Models\VoterModel;
use App\Models\CandidateModel;

class AdminController extends BaseController
{
    public function index()
    {
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }
    public function auth()
    {
        $session = session();
        $model   = new AdminModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $data = $model->verifyLogin($username, $password);
        if ($data) {
            $ses_data = [
                'admin_id'           => $data->id,
                'admin_name'         => $data->name,
                'admin_username'     => $data->username,
                'is_admin_logged_in' => true
            ];
            $session->set($ses_data);
            return redirect()->to('/admin/dashboard');
        } else {
            $session->setFlashdata('msg', 'Username atau Password Salah!');
            return redirect()->to('/admin');
        }
    }
    public function dashboard()
    {
        $session = session();
        if (!$session->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $voteModel      = new VoteModel();
        $voterModel     = new VoterModel();
        $candidateModel = new CandidateModel();
        $data = [
            'title'       => 'Dashboard Admin',
            'admin_name'  => $session->get('admin_name'),
            'total_voters'     => $voterModel->countAll(),
            'already_voted'    => $voterModel->where('status', 'voted')->countAllResults(),
            'total_candidates' => $candidateModel->countAll(),
            'vote_results'     => $voteModel->getQuickCount() 
        ];
        return view('admin/dashboard', $data);
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin');
    }

    /**
     * Halaman Hasil Voting
     */
    public function results()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $voteModel      = new VoteModel();
        $voterModel     = new VoterModel();
        $candidateModel = new CandidateModel();

        $totalVoters   = (int) $voterModel->countAll();
        $alreadyVoted  = (int) $voterModel->where('status', 'voted')->countAllResults();
        $voteResults   = $voteModel->getQuickCount();

        // Calculate percentages and find winner
        $results = [];
        $maxVotes = 0;
        $winnerId = null;
        
        foreach ($voteResults as $result) {
            $totalVotes = (int) $result->total_votes;
            $percentage = $alreadyVoted > 0 ? round(($totalVotes / $alreadyVoted) * 100, 1) : 0;
            
            $results[] = (object) [
                'id'                 => $result->id,
                'order_number'       => $result->order_number,
                'chairman_name'      => $result->chairman_name,
                'image'              => $result->image,
                'total_votes'        => $totalVotes,
                'percentage'         => $percentage,
            ];
            
            if ($totalVotes > $maxVotes) {
                $maxVotes = $totalVotes;
                $winnerId = $result->id;
            }
        }

        // Get full candidate data for winner
        $winner = null;
        if ($winnerId && $maxVotes > 0) {
            $winner = $candidateModel->find($winnerId);
        }

        $data = [
            'title'          => 'Hasil Voting',
            'total_voters'   => $totalVoters,
            'already_voted'  => $alreadyVoted,
            'not_voted'      => $totalVoters - $alreadyVoted,
            'vote_percentage'=> $totalVoters > 0 ? round(($alreadyVoted / $totalVoters) * 100, 1) : 0,
            'vote_results'   => $results,
            'winner'         => $winner,
            'winner_votes'   => $maxVotes,
        ];

        return view('admin/result/index', $data);
    }

    /**
     * API endpoint untuk real-time dashboard stats
     */
    public function dashboardStats()
    {
        if (!session()->get('is_admin_logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $voteModel  = new VoteModel();
        $voterModel = new VoterModel();

        $totalVoters  = (int) $voterModel->countAll();
        $alreadyVoted = (int) $voterModel->where('status', 'voted')->countAllResults(false);
        $voteResults  = $voteModel->getQuickCount();

        // Calculate percentages for each candidate
        $results = [];
        foreach ($voteResults as $result) {
            $totalVotes = (int) $result->total_votes;
            $percentage = $alreadyVoted > 0 ? round(($totalVotes / $alreadyVoted) * 100, 1) : 0;
            $results[] = [
                'id'          => $result->id,
                'order_number' => $result->order_number,
                'chairman_name' => $result->chairman_name,
                'total_votes' => $totalVotes,
                'percentage'  => $percentage,
            ];
        }

        // Get recent voters (last 10)
        $recentVoters = $this->db->table('voters')
            ->select('voters.name, votes.voted_at')
            ->join('votes', 'votes.voter_id = voters.id')
            ->orderBy('votes.voted_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();

        $recentVotersFormatted = [];
        foreach ($recentVoters as $voter) {
            $recentVotersFormatted[] = [
                'name'     => $voter->name,
                'voted_at' => date('H:i', strtotime($voter->voted_at)),
            ];
        }

        return $this->response->setJSON([
            'total_voters'    => $totalVoters,
            'already_voted'   => $alreadyVoted,
            'vote_percentage' => $totalVoters > 0 ? round(($alreadyVoted / $totalVoters) * 100, 1) : 0,
            'vote_results'    => $results,
            'recent_voters'   => $recentVotersFormatted,
        ]);
    }

    /**
     * SSE endpoint untuk real-time streaming
     */
    public function dashboardStream()
    {
        // Disable time limit for long-running connection
        set_time_limit(0);
        
        // Set headers untuk SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Disable nginx buffering
        
        // Flush any existing output
        while (ob_get_level()) ob_end_clean();
        
        $db = \Config\Database::connect();
        
        // Stream setiap 2 detik
        while (true) {
            // Check connection
            if (connection_aborted()) break;
            
            // Fresh query each time
            $totalVoters = (int) $db->table('voters')->countAll();
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
            
            echo "data: " . json_encode($data) . "\n\n";
            
            // Flush output
            if (ob_get_level()) ob_flush();
            flush();
            
            // Wait 2 seconds
            sleep(2);
        }
        
        exit;
    }
}