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

    public function getGalleries($projectId)
    {
        return $this->db->table('gallery_project')
            ->join('gallery', 'gallery.id = gallery_project.gallery_id')
            ->where('gallery_project.projects_id', $projectId)
            ->get()
            ->getResultArray();
    }

    public function addGallery($projectId, $galleryId)
    {
        return $this->db->table('gallery_project')->insert([
            'projects_id' => $projectId,
            'gallery_id' => $galleryId
        ]);
    }

    public function removeGallery($projectId, $galleryId)
    {
        return $this->db->table('gallery_project')
            ->where('projects_id', $projectId)
            ->where('gallery_id', $galleryId)
            ->delete();
    }
}