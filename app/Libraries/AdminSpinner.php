<?php
namespace App\Libraries;

class AdminSpinner
{
    public function load($active_nav): string
    {

        return view('admin/base/spinner');
    }
}
?>