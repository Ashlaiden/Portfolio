<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin;
use ReflectionException;

class Auth extends BaseController
{
    private int $email_interval;
    private $adminPrefix;
    private ?object $settings;

    public function __construct()
    {
        $this->settings = service('settings');           // CI4 Services factory :contentReference[oaicite:4]{index=4}
        $this->adminPrefix = $this->settings->get(
            'admin_prefix',
            env('ADMIN_DEFAULT_PREFIX')
        );
        $this->email_interval = $this->settings->get(
            'email_interval',
            env('EMAIL_DEFAULT_INTERVAL')
        );
    }

    /**
     * @throws ReflectionException
     */
    protected function checkowneradmin()
    {
        $adminModel = new Admin();
        $exists = $adminModel->where('role', 'owner')->countAllResults() > 0;
        $owner = $adminModel->where('role', 'owner')->first();

        if (!$exists) {
            return redirect()->to(site_url($this->adminPrefix . '/create-owner-account'));
        } elseif ($owner['status'] !== '1') {
            $adminModel->update($owner['id'], [
                'status' => 1,
            ]);
        } elseif (count($adminModel->where('role', 'owner')->findAll()) > 1) {
            $primaryOwner = $adminModel
                ->orderBy('created_at', 'asc')
                ->first();
            $allOwners = $adminModel->where('role', 'owner')->findAll();
            foreach ($allOwners as $owner) {
                if ($owner['id'] !== $primaryOwner['id']) {
                    $adminModel->delete($owner['id']);
                }
            }
        }
    }

    public function index()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }

        if (session('isAdmin') !== true) {
            $data = [
                'meta_title' => 'Login',
            ];

            return view('admin/auth', $data);
        } else {
            return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
        }
    }

    public function verify_Page()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        $data = [
            'meta_title' => 'Verification',
            'email_interval' => $this->email_interval,
        ];

        return view('admin/verify', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function login()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        helper(['form']);

        // Show login form on GET
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        // Handle the POST
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $validation = \Config\Services::validation();

        $rules = [
            'email' => [
                'label' => 'Email Address',
                'rules' => 'required|valid_email|max_length[255]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'max_length' => 'Email can\'t be longer than 255 characters.',
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]|max_length[100]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 6 characters.',
                    'max_length' => 'Password can\'t be longer than 100 characters.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminModel = new Admin();
        $admin = $adminModel->where('email', $email)->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Email not found.');
        }

        if (!password_verify($password, $admin['password'])) {
            return redirect()->back()->with('error', 'Invalid password.');
        }

        if ((int) $admin['status'] !== 1) {
            return redirect()->back()->with('error', 'Account is inactive.');
        }

        session()->set([
            'admin_id' => $admin['id'],
            'admin_role' => $admin['role'],
            'admin_email' => $admin['email'],
            'isAdmin' => false,
        ]);

        // Send code to email
        $this->sendVerificationCode($admin['email']);

        return redirect()->to($this->adminPrefix . '/login/verify');
    }

    /**
     * @throws ReflectionException
     */
    public function verify()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        if ($this->request->getMethod() !== 'POST') {
            if (!session()->has('auth_code')) {
                if (session('isAdmin') !== true) {
                    session()->destroy();
                    return redirect()->to(site_url($this->adminPrefix . '/login'));
                } else {
                    return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
                }
            }
            $currentTime = time();
            $codeSentTime = session()->get('code_time');

            // EXPIRE after 5 minutes
            if ($currentTime - $codeSentTime >= 300 | session('code_sent_count') > 3) {
                session()->remove('verify_progress');
                session()->remove('auth_code');
                session()->remove('code_time');
                session()->remove('code_sent_count');
                return redirect()->to(site_url($this->adminPrefix . '/login/verify/expired'));
            } else {
                $data = [
                    'meta_title' => 'Verification',
                    'code_time' => session()->get('code_time'),
                    'email_interval' => $this->email_interval,
                ];
                return view('admin/verify', $data);
            }
        }

        $inputCode = $this->request->getPost('code');

        $validation = \Config\Services::validation();

        $rules = [
            'code' => [
                'label' => 'Verification Code',
                'rules' => 'required|numeric|min_length[6]|max_length[6]',
                'errors' => [
                    'required' => 'Verification code is required.',
                    'numeric' => 'The code is Wrong.',
                    'min_length' => 'The code is Wrong.',
                    'max_length' => 'The code is Wrong.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $storedCode = session('auth_code');

        if ($inputCode == $storedCode) {
            session()->remove('verify_progress');
            session()->remove('auth_code');
            session()->remove('code_time');
            session()->remove('code_sent_count');
            session()->set('isAdmin', true);

            $adminModel = new Admin();
            $admin = $adminModel->find(session()->get('admin_id'));
            $last_login = $admin['last_login'];
            // Optional: update last login
            $adminModel->update($admin['id'], [
                'this_login' => date('Y-m-d H:i:s'),
                'last_login' => $last_login
            ]);

            return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
        } else {
            return redirect()->back()->with('error', 'Invalid verification code');
        }
    }

    public function resend_code()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        if (!session()->has('auth_code')) {
            if (session('isAdmin') !== true) {
                session()->destroy();
                return redirect()->to(site_url($this->adminPrefix . '/login'));
            } else {
                return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
            }
        }
        $currentTime = time();
        $codeSentTime = session()->get('code_time');

        // EXPIRE after 5 minutes
        if ($currentTime - $codeSentTime >= 300) {
            session()->remove('verify_progress');
            session()->remove('auth_code');
            session()->remove('code_time');
            session()->remove('code_sent_count');
            return redirect()->to(site_url($this->adminPrefix . '/login/verify/expired'));
        } else {
            if ($currentTime - $codeSentTime <= $this->email_interval) {
                $data = [
                    'success' => false,
                    'message' => 'Request Not completed!',
                    'code' => 200,
                    'time_to_left' => ($this->email_interval - ($currentTime - $codeSentTime)),
                    'code_time' => session()->get('code_time'),
                ];
                //                return $this->response->setJSON($data);

                return response()->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setJSON($data);
            }
            if (session()->get('code_sent_count') < 3) {
                // Send code to email
                $this->sendVerificationCode(session()->get('admin_email'));
            } else {
                if ($currentTime - session()->get('code_time') > 300) {
                    // Send code to email
                    $this->sendVerificationCode(session()->get('admin_email'));

                    $data = [
                        'success' => true,
                        'message' => 'Request completed!',
                        'code' => 200,
                        'time_to_left' => ($this->email_interval - ($currentTime - $codeSentTime)),
                        'code_time' => session()->get('code_time'),
                    ];
                    //                return $this->response->setJSON($data);

                    return response()->setContentType('application/json')
                        ->setStatusCode(200)
                        ->setJSON($data);
                }
                return redirect()->to(site_url($this->adminPrefix . '/login/verify/expired'));
            }
            $data = [
                'success' => true,
                'message' => 'Request completed!',
                'code' => 200,
                'code_time' => session()->get('code_time'),
                'time_to_left' => ($this->email_interval - ($currentTime - $codeSentTime)),
            ];
            //            return $this->response->setJSON($data);

            return response()->setContentType('application/json')
                ->setStatusCode(200)
                ->setJSON($data);
        }
    }

    public function expired(): string
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        $data = [
            'meta_title' => 'Verification',
        ];
        return view('admin/expired_verify', $data);
    }

    protected function sendVerificationCode($adminEmail)
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        $email = \Config\Services::email();

        if (!$adminEmail) {
            echo '⚠️ adminUsername is not set in your panel file';
            die();
        }

        $code = rand(100000, 999999);

        //        $email->setFrom('support@ramtin683520.serv00.net', 'Ashtin Admin');
        $email->setTo($adminEmail);
        $email->setSubject('🛡️ Admin Login Verification Code');
        $email->setMailType('html');
        // ✅ Add basic styling and a real footer
        $html = "
        <html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .code { font-size: 1.2em; font-weight: bold; background: #f3f3f3; padding: 10px; border-radius: 6px; }
          </style>
        </head>
        <body>
          <p>Verification</p>
          <p>Your Verification code is:</p>
          <div class=\"code\">$code</div>
          <p>— Ashlaiden Security Team</p>
        </body>
        </html>
        ";


        $email->setMessage($html);

        $email->send();

        session()->set([
            'verify_progress' => true,
            'auth_code' => $code,
            'code_time' => time(),
            'code_sent_count' => session()->has('code_sent_count') ? session()->get('code_sent_count') + 1 : 1,
        ]);

        //        var_dump(session()->get());
    }

    /**
     * @throws ReflectionException
     */
    public function forgotpassword()
    {
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        if ($this->request->getMethod() !== 'POST') {
            $data = [
                'meta_title' => 'Forgot Password',
            ];
            return view('admin/forgotpassword', $data);
        }

        if (session('isAdmin') == true) {
            return redirect()->to(site_url($this->adminPrefix . '/dashboard'));
        }

        // Handle the POST
        $email = $this->request->getPost('email');

        $validation = \Config\Services::validation();

        $rules = [
            'email' => [
                'label' => 'Email Address',
                'rules' => 'required|valid_email|max_length[255]|is_exist[admin.email]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'max_length' => 'Email can\'t be longer than 255 characters.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminModel = new Admin();
        $admin = $adminModel->where('email', $email)->first();
        if (!$admin) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $email = \Config\Services::email();

        $password = rand(10000000, 99999999);

        //        $email->setFrom('support@ramtin683520.serv00.net', 'Ashtin Admin');
        $email->setTo($admin['email']);
        $email->setSubject('🛡️ Admin Login Verification Code');
        $email->setMailType('html');
        // ✅ Add basic styling and a real footer
        $html = "
        <html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .code { font-size: 1.2em; font-weight: bold; background: #f3f3f3; padding: 10px; border-radius: 6px; }
          </style>
        </head>
        <body>
          <p>Password Reset</p>
          <p>Your New Password is:</p>
          <div class=\"code\">$password</div>
          <p>— Ashlaiden Security Team</p>
        </body>
        </html>
        ";


        $email->setMessage($html);

        $email->send();

        $adminModel->update($admin['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return view('admin/password_sent', ['meta_title' => 'Email Sent']);
    }

    public function logout()
    {
        session()->destroy();
        if ($response = $this->checkowneradmin()) {
            return $response;
        }
        return redirect()->to(site_url($this->adminPrefix . '/login'));
    }

    /**
     * @throws ReflectionException
     */
    public function createowneraccount()
    {
        $adminModel = new Admin();
        $exists = $adminModel->where('role', 'owner')->countAllResults() >= 1;
        $admin = $adminModel->where('role', 'owner')->first();

        if ($exists) {
            if ($admin['status'] !== '1') {
                $adminModel->update($admin['id'], [
                    'status' => 1,
                ]);
            }
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (count($adminModel->findAll()) > 1) {
            foreach ($adminModel->findAll() as $admin) {
                $adminModel->update($admin['id'], [
                    'status' => 0
                ]);
            }
        }

        if ($this->request->getMethod() !== 'POST') {
            $data = [
                'meta_title' => 'Create Owner Account',
            ];

            return view('admin/createowneraccount', $data);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
        ];

        if (!$adminModel->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $adminModel->errors());
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = 'owner';
        $data['status'] = '1';

        $adminModel = new Admin();
        $adminModel->save([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/login'));
    }
}


