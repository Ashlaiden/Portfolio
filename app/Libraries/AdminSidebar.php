<?php
namespace App\Libraries;

class AdminSidebar
{
    public function load($active): string
    {
        return view('admin/base/sidebar', $active);
    }
}
?>