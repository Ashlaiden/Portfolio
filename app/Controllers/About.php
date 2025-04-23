<?php
namespace App\Controllers;

class about extends BaseController
{
    public function index(): string
    {
        $data = [
            'meta_title' => 'About',
            'active_nav' => 'about'
        ];
        return view('about', $data);
    }
}
