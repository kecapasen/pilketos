<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateModel extends Model
{
    protected $table            = 'candidates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $allowedFields    = [
        'order_number',
        'chairman_name',
        'chairman_class',
        'vice_chairman_name',
        'vice_chairman_class',
        'image',
        'vision',
        'mission'
    ];
    protected $validationRules = [
        'id'                  => 'permit_empty|is_natural_no_zero',
        'order_number'        => 'required|is_unique[candidates.order_number,id,{id}]', // No Urut harus unik
        'chairman_name'       => 'required',
        'chairman_class'      => 'required',
        'vision'              => 'required',
        'mission'             => 'required',
    ];
    protected $validationMessages = [
        'order_number' => [
            'is_unique' => 'Nomor urut ini sudah dipakai kandidat lain.'
        ]
    ];
}