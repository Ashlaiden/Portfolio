<?php

namespace App\Models;

use CodeIgniter\Model;

class Gallery extends Model
{
    protected $table      = 'gallery';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'image_path', 'on_delete', 'deleted_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; // no updates needed
    protected $useSoftDeletes = false;

    public function getProjects($galleryId)
    {
        return $this->db->table('gallery_project')
            ->join('projects', 'projects.id = gallery_project.projects_id')
            ->where('gallery_project.gallery_id', $galleryId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get the single project attached to a gallery image.
     *
     * @param int $galleryId
     * @return object|null  // stdClass with project fields, or null if none
     */
    public function getProject(int $galleryId)
    {
        $result = $this->db
            ->table('gallery_project')
            ->select('projects.id, projects.title, projects.on_delete')
            ->join('projects', 'projects.id = gallery_project.projects_id')
            ->where('gallery_project.gallery_id', $galleryId)
            ->limit(1)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    public function addProject($galleryId, $projectId)
    {
        return $this->db->table('gallery_project')->insert([
            'gallery_id' => $galleryId,
            'projects_id' => $projectId
        ]);
    }

    public function removeProject($galleryId, $projectId)
    {
        return $this->db->table('gallery_project')
            ->where('gallery_id', $galleryId)
            ->where('projects_id', $projectId)
            ->delete();
    }
}