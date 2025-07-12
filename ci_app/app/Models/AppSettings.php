<?php

namespace App\Models;

use CodeIgniter\Model;

class AppSettings extends Model
{
    protected $table      = 'app_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'key_name', 'value'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = 'updated_at'; // no updates needed
    protected $useSoftDeletes = false;
}