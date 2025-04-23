<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'email',
        'password',
        'role',
        'status',
        'this_login',
        'last_login',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType    = 'array';

    // Optional: Add validation rules
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'username' => 'required|min_length[3]|max_length[50]|is_unique[admin.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[admin.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]',
        'role'     => 'in_list[owner,superadmin,admin,editor]',
        'status'   => 'in_list[0,1]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
}