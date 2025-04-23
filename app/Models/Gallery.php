<?php

namespace App\Models;

use CodeIgniter\Model;

class Gallery extends Model
{
    protected $table      = 'gallery';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'project_id', 'image_path', 'title'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // no updates needed
    protected $useSoftDeletes = false;
}