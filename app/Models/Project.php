<?php

namespace App\Models;

use CodeIgniter\Model;

class Project extends Model
{
    protected $table      = 'projects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'slug', 'programming_language', 'framework', 'description', 'image', 'deleted_at', 'on_delete'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; // no updates needed
    protected $useSoftDeletes = false;
}