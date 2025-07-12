<?php
namespace App\Libraries;

use App\Models\Project;
use App\Models\Gallery;

class Header{
    public function load($active_nav): string
    {
        $projects = new Project();
        if ($projects->countAll() > 0) {
            $have_projects = true;
        } else {
            $have_projects = false;
        }

        $galleryModel = new Gallery();
        if ($galleryModel->countAll() > 0) {
            $have_gallery = true;
        } else {
            $have_gallery = false;
        }

        $isAdmin = false;

        if (session()->has('isAdmin') && session()->get('isAdmin')) {
            $isAdmin = true;
        }

        $data = [
            'have_projects' => $have_projects,
            'have_gallery' => $have_gallery,
            'isAdmin' => $isAdmin,
        ];

        return view('base/header' , $data);
    }
}
?>