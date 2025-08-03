<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin;
use ReflectionException;


class Setting extends BaseController
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

    public function index()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $settingService = service('settings');
        $settingService->refreshCache();
        $settings = $settingService->getAll();

        $adminModel = new Admin();
        $admins = $adminModel->findAll();
        $logedinadmin = $adminModel->find(session('admin_id'));

        $data = [
            'meta_title' => 'Setting',
            'active' => 'setting',
            'settings' => $settings,
            'admins' => $admins,
            'logedinadmin' => $logedinadmin,
        ];

        return view('admin/setting', $data);
    }

    public function updatesetting()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }
        
        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(site_url($this->adminPrefix . '/setting'));
        }

        $postData = $this->request->getPost();

        // Step 1: Define your rules for known setting keys
        $validationRules = [];
        foreach ($postData as $key => $value) {
            $validationRules[$key] = match ($key) {
                'admin_prefix' => [
                    'rules' => 'required|min_length[3]|validPath',
                    'errors' => [
                        'validPath' => 'The {field} field may only contain letters, numbers, slashes (/), dashes (-), and underscores (_).',
                    ]
                ],
                default => 'permit_empty',
            };
        }

        // Step 2: Validate
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Step 3: Save to DB
        $setting = service('settings');
        foreach ($postData as $key => $value) {
            if ($key == 'admin_prefix') {
                $value = trim($value, '/');
            }
            $setting->set($key, $value);
        }

        $setting->refreshCache();

        $this->adminPrefix = $this->settings->get(
            'admin_prefix',
            env('ADMIN_DEFAULT_PREFIX')
        );

        return redirect()->to(site_url($this->adminPrefix . '/setting'));

    }

    /**
     * @throws ReflectionException
     */
    public function newadmin()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }
        
        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            $data = [
                'meta_title' => 'Add Admin',
                'active' => 'setting',
                'adminrole' => 'superadmin'
            ];

            return view('admin/newadmin', $data);
        }

        $adminModel = new Admin();

        // 👇 Clean form data
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('adminrole'),
            'status'   => $this->request->getPost('Active') === 'on' ? 1 : 0,
        ];

        // 👇 Validate data
        if (!$adminModel->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $adminModel->errors());
        }

        // 👇 Hash the password securely
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);


        // 👇 Insert into DB
        $adminModel->save($data);

        // 👇 Redirect with success flash message
        return redirect()->to(site_url($this->adminPrefix . '/setting'));

    }

    public function editadmin($id = null)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }
        
        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $adminModel = new Admin();

        if ($this->request->getMethod() !== 'POST') {
            $admin = $adminModel->find($id);
            if (!$admin) {
                return redirect()->to(site_url($this->adminPrefix . '/setting'));
            }

            if ($id == session()->get('admin_id')) {
                return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
            } elseif ($admin['role'] == 'owner') {
                return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
            }

            $data = [
                'meta_title' => 'Add Admin',
                'active' => 'setting',
                'adminrole' => 'superadmin',
                'admin' => $admin
            ];

            return view('admin/editadmin', $data);
        }

        $adminID = $this->request->getPost('id');
        $admin = $adminModel->find($adminID);
        if (!$admin) {
            return redirect()->to(site_url($this->adminPrefix . '/setting'));
        }

        if ($adminID == session()->get('admin_id')) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        } elseif ($admin['role'] == 'owner') {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        // 👇 Clean form data
        $data = [
            'id' => $this->request->getPost('id'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('adminrole'),
            'status'   => $this->request->getPost('Active') === 'on' ? 1 : 0,
        ];

        // 👇 Validate data
        if (!$adminModel->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $adminModel->errors());
        }

        $password = $this->request->getPost('password');
        if (str_replace(' ', '', $password) !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $data['password'] = $admin['password'];
        }
        
        $adminModel->update($adminID, $data);
        return redirect()->to(site_url($this->adminPrefix . '/setting'));

    }

    public function deleteadmin()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        } elseif (session()->get('admin_role') !== 'owner') {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(site_url($this->adminPrefix . '/setting'));
        }

        $adminModel = new Admin();
        $adminID = $this->request->getPost('id');
        $admin = $adminModel->find($adminID);
        if (!$admin) {
            return redirect()->to(site_url($this->adminPrefix . '/setting'));
        }

        if ($adminID == session()->get('admin_id')) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        } elseif ($admin['role'] == 'owner') {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        // 👇 Insert into DB
        $adminModel->delete($adminID);

        // 👇 Redirect with success flash message
        return redirect()->to(site_url($this->adminPrefix . '/setting'));

    }

    /**
     * @throws ReflectionException
     */
    public function editprofile()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $adminModel = new Admin();

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(site_url($this->adminPrefix . '/setting'));
        }

        // 👇 Clean form data
        $data = [
            'id' => $this->request->getPost('id'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        // 👇 Validate data
        if (!$adminModel->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $adminModel->errors());
        }

        $admin = $adminModel->find($data['id']);
        if (!$admin || $admin['id'] !== session()->get('admin_id')) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $currentPassword = $this->request->getPost('currentpassword');
        if (!password_verify($currentPassword, str_replace(' ', '', $$admin['password']))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['Please enter Current Password Correctly!']);
        }

        $passwordInput = $this->request->getPost('password');
        if ((str_replace(' ', '', $passwordInput) != '')) {
            $data['password'] = password_hash($passwordInput, PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Don't touch the password if it's blank
        }

        // 👇 Insert into DB
        $adminModel->update($data['id'], $data);

        // 👇 Redirect with success flash message
        return redirect()->to(site_url($this->adminPrefix . '/setting'));

    }
}













