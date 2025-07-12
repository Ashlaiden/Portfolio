<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryProject extends Model
{
    protected $table            = 'gallery_project';
    protected $primaryKey       = ''; // composite key, not needed unless you want to use find()
    protected $allowedFields    = ['gallery_id', 'projects_id'];
    public $useTimestamps       = false;
}
