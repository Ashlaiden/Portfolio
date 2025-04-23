<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\URI;
use App\Models\View;
use App\Models\Project;

class Dashboard extends BaseController
{
    private $adminPrefix;
    private ?object $settings;

    public function __construct()
    {
        $this->settings = service('settings');           // CI4 Services factory :contentReference[oaicite:4]{index=4}
        $this->adminPrefix = $this->settings->get(
            'admin_prefix',
            env('ADMIN_DEFAULT_PREFIX')
        );
    }
    
    public function index(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        } else {
            return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
        }
    }

    public function dashboard() {

        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

//        create models
        $viewModel = new View();
        $uriModel = new URI();
        $projectModel = new Project();

//        create data should sent to page
        $today_view = 0;
        $total_view = 0;
        $total_project = 0;
        $projects = $projectModel->findAll();


//        start assigning data
        $all_uri = $uriModel->orderBy('uri', 'ASC')->findAll();

        foreach ($all_uri as $uri) {
            $total_view += $uri['view_count'];
        }
        $today_view += $uriModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults();

        $total_project += $projectModel->countAllResults();

//        final data array
        $data = [
            'meta_title' => 'Dashboard',
            'active' => 'dashboard',
            'today_view' => $today_view,
            'total_view' => $total_view,
            'total_project' => $total_project,
            'projects' => $projects,
        ];
        return view('admin/dashboard', $data);
    }

}












