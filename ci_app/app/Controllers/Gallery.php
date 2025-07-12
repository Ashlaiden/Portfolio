<?php

namespace App\Controllers;

use App\Models\Gallery as GalleryModel;
//use App\Models\Project;

class Gallery extends BaseController
{
    public function index(): string
    {
        $galleryModel = new GalleryModel();
        $galleries = $galleryModel->where('on_delete', false)->findAll();
        $data = [
            'meta_title' => 'Gallery',
            'active_nav' => 'gallery',
            'galleries' => $galleries
        ];
        return view('gallery', $data);
    }

    public function projectdetail($id)
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);

        if (!$id || !$project || $project['on_delete'] == true) {
            return redirect()->to('/projects');
        }

        $galleryModel = new Gallery();
        $gallery = $galleryModel->where('project_id', $id)->findAll();

        $data = [
            'meta_title' => 'Project Detail',
            'active_nav' => 'projects',
            'project' => $project,
            'galleries' => $gallery
        ];
        return view('projectdetail', $data);
    }

}












