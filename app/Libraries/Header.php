<?php
namespace App\Libraries;

use App\Models\Project;

class Header{
    public function load($active_nav): string
    {
        $projects = new Project();
        if ($projects->countAll() > 0) {
            $have_projects = true;
        } else {
            $have_projects = false;
        }

        $isAdmin = false;

        if (session()->has('isAdmin') && session()->get('isAdmin') == true) {
            $isAdmin = true;
        }

        $data = [
            'have_projects' => $have_projects,
            'isAdmin' => $isAdmin,
        ];

        return view('base/header' , $data);
    }
}
?>