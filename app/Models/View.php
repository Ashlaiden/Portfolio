<?php

namespace App\Models;

use CodeIgniter\Model;

class View extends Model
{
    protected $table      = 'views';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'session_id', 'ip_address', 'device_name', 'country', 'uri', 'page_id'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // no updates needed
}