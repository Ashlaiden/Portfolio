<?php

namespace App\Controllers;

use App\Models\Gallery;
use App\Models\Project;

class Projects extends BaseController
{
    public function index()
    {
        $projectModel = new Project();
        $projects = $projectModel->where('on_delete', false)->findAll();
        $data = [
            'meta_title' => 'Projects',
            'active_nav' => 'projects',
            'projects' => $projects
        ];
        return view('projects', $data);
    }

    public function projectdetail($id)
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);

        if (!$id || !$project || $project['on_delete'] == true) {
            return redirect()->to('/projects');
        }

        $gallery = $projectModel->getGalleries($id);

        $data = [
            'meta_title' => 'Project Detail',
            'active_nav' => 'projects',
            'project' => $project,
            'galleries' => $gallery
        ];
        return view('projectdetail', $data);
    }

}












