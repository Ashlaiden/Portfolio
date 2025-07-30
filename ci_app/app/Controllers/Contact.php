<?php
namespace App\Controllers;

class Contact extends BaseController
{
    private ?object $settings;
    private $contactPageActive;

    public function __construct()
    {
//        parent::__construct();

        $this->settings = service('settings');           // CI4 Services factory :contentReference[oaicite:4]{index=4}
        $this->contactPageActive = $this->settings->get(
            'contact_page_active',
            env('CONTACT_PAGE_ACTIVE')
        );
        helper(['setting']);
    }

    public function index()
    {
        if ($this->contactPageActive) {
            return redirect()->to(site_url('/'));
        }
        $data = [
            'meta_title' => 'Contact',
            'active_nav' => 'contact'
        ];
        return view('contact', $data);
    }
}
