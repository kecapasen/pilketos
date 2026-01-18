<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VoterModel;

class VoterController extends BaseController
{
    protected $voterModel;
    public function __construct()
    {
        $this->voterModel = new VoterModel();
    }
    public function index()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $data = [
            'title'  => 'Manajemen Pemilih (DPT)',
            'voters' => $this->voterModel->orderBy('class_group', 'ASC')->findAll()
        ];
        return view('admin/voter/index', $data);
    }
    public function store()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $token = $this->request->getVar('token');
        if (empty($token)) {
            $token = strtoupper(bin2hex(random_bytes(3)));
        }
        $data = [
            'name'        => $this->request->getVar('name'),
            'class_group' => $this->request->getVar('class_group'),
            'token'       => $token,
            'status'      => 'not_voted'
        ];
        if ($this->voterModel->save($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->voterModel->errors());
        }
        session()->setFlashdata('success', 'Pemilih berhasil ditambahkan. Token: ' . $token);
        return redirect()->to('/admin/voters');
    }
    public function bulkGenerate()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $jumlah = $this->request->getPost('amount');
        $kelas  = $this->request->getPost('class_group');
        $successCount = 0;
        for ($i = 0; $i < $jumlah; $i++) {
            $token = strtoupper(bin2hex(random_bytes(3)));
            $data = [
                'name'        => 'Siswa ' . $kelas . ' - ' . ($i + 1),
                'class_group' => $kelas,
                'token'       => $token,
                'status'      => 'not_voted'
            ];
            if ($this->voterModel->insert($data)) {
                $successCount++;
            }
        }
        session()->setFlashdata('success', "Berhasil generate $successCount token untuk kelas $kelas.");
        return redirect()->to('/admin/voters');
    }
    public function delete($id)
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $this->voterModel->delete($id);
        session()->setFlashdata('success', 'Data pemilih berhasil dihapus.');
        return redirect()->to('/admin/voters');
    }
    public function resetStatus($id)
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $this->voterModel->resetVoterStatus($id);
        session()->setFlashdata('success', 'Status pemilih berhasil di-reset. Bisa login kembali.');
        return redirect()->to('/admin/voters');
    }
    public function clearAll()
    {
         if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $this->voterModel->truncate();
        session()->setFlashdata('success', 'Semua data pemilih berhasil dibersihkan.');
        return redirect()->to('/admin/voters');
    }
}