<?php

namespace App\Models;

use CodeIgniter\Model;

class URI extends Model
{
    protected $table      = 'uri';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'uri', 'view_count'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // no updates needed
}