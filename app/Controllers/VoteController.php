<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\VoterModel;
use App\Models\VoteModel;

class VoteController extends BaseController
{
    protected $candidateModel;
    protected $voterModel;
    protected $voteModel;
    protected $db;
    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->voterModel     = new VoterModel();
        $this->voteModel      = new VoteModel();
        $this->db             = \Config\Database::connect();
    }
    public function index()
    {
        if (session()->get('is_voter_logged_in')) {
            return redirect()->to('/bilik-suara');
        }
        return view('home/login');
    }
    public function login()
    {
        $token = $this->request->getVar('token');
        $voter = $this->voterModel->getVoterByToken($token);
        if (!$voter) {
            return redirect()->back()->with('error', 'Token tidak ditemukan / tidak valid.');
        }
        if ($voter->status == 'voted') {
            return redirect()->back()->with('error', 'Token ini sudah digunakan untuk memilih.');
        }
        $sessionData = [
            'voter_id'           => $voter->id,
            'voter_name'         => $voter->name,
            'voter_class'        => $voter->class_group,
            'is_voter_logged_in' => true
        ];
        session()->set($sessionData);
        return redirect()->to('/bilik-suara');
    }
    public function bilikSuara()
    {
        if (!session()->get('is_voter_logged_in')) {
            return redirect()->to('/');
        }
        $voterId = session()->get('voter_id');
        $voter   = $this->voterModel->find($voterId);
        if ($voter->status == 'voted') {
            session()->destroy();
            return redirect()->to('/')->with('error', 'Anda sudah memilih sebelumnya.');
        }
        $data = [
            'title'      => 'Bilik Suara E-Voting',
            'voter'      => $voter,
            'candidates' => $this->candidateModel->orderBy('order_number', 'ASC')->findAll()
        ];
        return view('home/bilik_suara', $data);
    }
    public function submitVote()
    {
        if (!session()->get('is_voter_logged_in')) {
            return redirect()->to('/');
        }
        $voterId     = session()->get('voter_id');
        $candidateId = $this->request->getPost('candidate_id');
        $this->db->transStart();
        if ($this->voteModel->hasVoted($voterId)) {
            $this->db->transRollback();
            session()->destroy();
            return redirect()->to('/')->with('error', 'Curang terdeteksi: Anda sudah memilih!');
        }
        $this->voteModel->save([
            'voter_id'     => $voterId,
            'candidate_id' => $candidateId,
            'ip_address'   => $this->request->getIPAddress()
        ]);
        $this->voterModel->markAsVoted($voterId);
        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
        
        // Broadcast real-time update via Pusher
        try {
            $pusher = new \App\Libraries\PusherService();
            $pusher->broadcastVoteUpdate();
        } catch (\Exception $e) {
            // Log error but don't block the vote
            log_message('error', 'Pusher broadcast failed: ' . $e->getMessage());
        }
        
        session()->destroy();
        return redirect()->to('/sukses');
    }
    public function success()
    {
        return view('home/success');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}