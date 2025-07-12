<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'meta_title' => 'Home',
            'active_nav' => 'home'
        ];
        return view('home', $data);
    }
}
