<?php
namespace App\Controllers;

class contact extends BaseController
{
    public function index(): string
    {
        $data = [
            'meta_title' => 'Contact',
            'active_nav' => 'contact'
        ];
        return view('contact', $data);
    }
}
