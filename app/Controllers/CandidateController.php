<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CandidateModel;

class CandidateController extends BaseController
{
    protected $candidateModel;
    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
    }
    public function index()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $data = [
            'title'      => 'Kelola Kandidat',
            'candidates' => $this->candidateModel->orderBy('order_number', 'ASC')->findAll()
        ];
        return view('admin/candidate/index', $data);
    }
    public function create()
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $data = [
            'title' => 'Tambah Kandidat Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/candidate/create', $data);
    }
    public function store()
    {
        if (!$this->validate([
            'image' => [
                'rules' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih gambar kandidat terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar (max 2MB)',
                    'is_image' => 'File yang dipilih bukan gambar',
                    'mime_in'  => 'File yang dipilih bukan gambar'
                ]
            ]
        ])) {
            return redirect()->to('/admin/candidate/create')->withInput();
        }
        $fileImage = $this->request->getFile('image');
        if (! $fileImage->isValid() || $fileImage->hasMoved()) {
            return redirect()->to('/admin/candidate/create')->withInput();
        }
        $imageName = $fileImage->getRandomName();
        $uploadDir = FCPATH . 'uploads/candidates';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        $fileImage->move($uploadDir, $imageName);
        $data = [
            'order_number'        => $this->request->getVar('order_number'),
            'chairman_name'       => $this->request->getVar('chairman_name'),
            'chairman_class'      => $this->request->getVar('chairman_class'),
            'vice_chairman_name'  => $this->request->getVar('vice_chairman_name'),
            'vice_chairman_class' => $this->request->getVar('vice_chairman_class'),
            'vision'              => $this->request->getVar('vision'),
            'mission'             => $this->request->getVar('mission'),
            'image'               => $imageName
        ];
        if ($this->candidateModel->save($data) === false) {
            return redirect()->to('/admin/candidate/create')->withInput()->with('errors', $this->candidateModel->errors());
        }
        session()->setFlashdata('success', 'Kandidat berhasil ditambahkan.');
        return redirect()->to('/admin/candidates');
    }
    public function edit($id)
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $candidate = $this->candidateModel->find($id);
        if (!$candidate) {
            return redirect()->to('/admin/candidates');
        }
        $data = [
            'title'      => 'Edit Kandidat',
            'validation' => \Config\Services::validation(),
            'candidate'  => $candidate
        ];
        return view('admin/candidate/edit', $data);
    }
    public function update($id)
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $candidateOld = $this->candidateModel->find($id);
        if (!$candidateOld) {
            return redirect()->to('/admin/candidates');
        }
        $fileImage = $this->request->getFile('image');
        $uploadDir = FCPATH . 'uploads/candidates';
        if ($fileImage->getError() == 4) {
            $imageName = $this->request->getVar('old_image');
        } else {
            if (! $fileImage->isValid() || $fileImage->hasMoved()) {
                return redirect()->back()->withInput();
            }
            $imageName = $fileImage->getRandomName();
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $fileImage->move($uploadDir, $imageName);
            $oldPath = $uploadDir . '/' . $candidateOld->image;
            if ($candidateOld->image !== 'default.jpg' && is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        $data = [
            'id'                  => $id,
            'order_number'        => $this->request->getVar('order_number'),
            'chairman_name'       => $this->request->getVar('chairman_name'),
            'chairman_class'      => $this->request->getVar('chairman_class'),
            'vice_chairman_name'  => $this->request->getVar('vice_chairman_name'),
            'vice_chairman_class' => $this->request->getVar('vice_chairman_class'),
            'vision'              => $this->request->getVar('vision'),
            'mission'             => $this->request->getVar('mission'),
            'image'               => $imageName
        ];
        if ($this->candidateModel->save($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->candidateModel->errors());
        }
        session()->setFlashdata('success', 'Data kandidat berhasil diubah.');
        return redirect()->to('/admin/candidates');
    }
    public function delete($id)
    {
        if (!session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }
        $candidate = $this->candidateModel->find($id);
        if ($candidate->image != 'default.jpg' && file_exists('uploads/candidates/' . $candidate->image)) {
            unlink('uploads/candidates/' . $candidate->image);
        }
        $this->candidateModel->delete($id);
        session()->setFlashdata('success', 'Kandidat berhasil dihapus.');
        return redirect()->to('/admin/candidates');
    }
}