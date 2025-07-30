<?php namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Files\Exceptions\FileNotFoundException;

class Editor extends Controller
{
    // Whitelisted view files for editing (app/Views/)
    protected $editableViews:

    // Path to store encrypted temp copies and backups
    protected $tempPath;
    private $adminPrefix;
    private ?object $settings;

    public function __construct()
    {
        $this->settings = service('settings');           // CI4 Services factory :contentReference[oaicite:4]{index=4}
        $this->adminPrefix = $this->settings->get(
            'admin_prefix',
            env('ADMIN_DEFAULT_PREFIX')
        );

        // Encrypted temps in writable folder
        $this->tempPath = WRITEPATH . 'temp_views/';
        helper(['filesystem', 'url', 'setting']);

        if ($this->settings->get('contact_page_active', env('CONTACT_PAGE_ACTIVE'))) {
            $this->editableViews = [
                'home' => APPPATH . 'Views/home.php',
                'about' => APPPATH . 'Views/about.php',
            ];
        } else {
            $this->editableViews = [
                'home' => APPPATH . 'Views/home.php',
                'about' => APPPATH . 'Views/about.php',
                'contact' => APPPATH . 'Views/contact.php',
            ];
        }
    }

    private function enc_files($s)
    {
        $encrypter = \Config\Services::encrypter();

        foreach ($this->editableViews as $slug => $view) {
            // ensure base tempPath exists
            $dir = dirname($this->tempPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // create the *slug* subfolder directly
            $slugDir = $this->tempPath . $slug;
            if (!is_dir($slugDir)) {
                mkdir($slugDir, 0755, true);
            }

            $path = $this->editableViews[$slug];
            $encFile = $this->tempPath . $slug . '.enc';

            if (!is_file($encFile) && is_file($path) && $slug === $s) {
                $raw = file_get_contents($path);
                $cipher = $encrypter->encrypt($raw);
                write_file($encFile, $cipher);
            }
        }
    }


    /**
     * List editable pages and their backups
     */
    public function index()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        // Prepare pages list
        $pages = [];
        foreach ($this->editableViews as $slug => $path) {
            $encFile = $this->tempPath . $slug . '.enc';
            // Gather backups: pattern slug_backup_*.enc
            $backups = [];
            $files = get_filenames($this->tempPath . '/' . $slug . '/');
            foreach ($files as $file) {
                if (strpos($file, "{$slug}_backup_") === 0 && substr($file, -4) === '.enc') {
                    $backups[] = $file;
                }
            }
            sort($backups);
            $pages[] = [
                'slug' => $slug,
                'title' => ucfirst($slug),
                'backups' => $backups,
                'edited' => is_file($encFile),
            ];
        }
        $data = [
            'meta_title' => 'View Manager',
            'active' => 'editor',
            'pages' => $pages,
        ];
        return view('admin/viewmanager', $data);
    }

    /**
     * Show editor for a view file (decrypt temp if exists)
     */
    public function editView(string $slug)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (!isset($this->editableViews[$slug])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->enc_files($slug);
        $encFile = $this->tempPath . $slug . '.enc';
        $encrypter = \Config\Services::encrypter();

        // Load and decrypt temp for editing
        if (is_file($encFile)) {
            $cipher = file_get_contents($encFile);
            $content = $encrypter->decrypt($cipher);
        } else {
            $content = '';
        }

        return view('admin/edit_view', [
            'slug' => $slug,
            'content' => $content,
            'meta_title' => 'Editor',
            'active' => 'editor',
        ]);
    }

    /**
     * Show Preview of Editing File
     */
    public function preview(string $slug)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $content = $this->request->getPost('content');

        $tempPath = $this->tempPath . '/preview.php';
        file_put_contents($tempPath, $content);

        $data = [
            'meta_title' => 'Preview',
            'active_nav' => $slug,
        ];
        $renderer = \Config\Services::renderer($this->tempPath);
        $renderer->setData($data);
        return $renderer->render('preview');
    }

    /**
     * Set Editing File To Default Page Content
     */
    public function makeDefault() {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $slug = $this->request->getPost('slug');
        $encFile = $this->tempPath . $slug . '.enc';

        if (!file_exists($encFile)) {
            unlink($encFile);
            $this->enc_files($slug);
            return redirect()->to(site_url($this->adminPrefix . '/view-manager'));
        } else {
            return redirect()->to(site_url($this->adminPrefix . '/view-manager'));
        }
    }

    /**
     * Save edited content to encrypted temp
     */
    public function saveView()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $slug = $this->request->getPost('slug');

        if (!isset($this->editableViews[$slug])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $encFile = $this->tempPath . $slug . '.enc';
        $encrypter = \Config\Services::encrypter();

        $newContent = $this->request->getPost('content');

        // Encrypt and save new content to temp
        $cipher = $encrypter->encrypt($newContent);
        if (!write_file($encFile, $cipher)) {
            return redirect()->back()->with('error', 'Failed to save temp view.');
        }

        return redirect()->to(site_url($this->adminPrefix . '/view-manager'));
    }

    /**
     * Publish Edited File or a specific backup
     */
    public function publishView()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $slug = $this->request->getPost('slug');
        $backup = $this->request->getPost('backup') ? $this->request->getPost('backup') : null;

        $path = $this->editableViews[$slug];
        $encFile = $this->tempPath . $slug . '.enc';
        $encrypter = \Config\Services::encrypter();
        $content = '';

        // Backup original view encrypted
        $backupRaw = is_file($path)
            ? file_get_contents($path)
            : null;
        if ($backupRaw) {
            $encryptedBackupRaw = $encrypter->encrypt($backupRaw);
            $timestamp = date('Y-m-d__H-i-s');
            $rawPath = $this->tempPath . '/' . $slug . '/' . "{$slug}_backup_{$timestamp}.enc";
            if (!write_file($rawPath, $encryptedBackupRaw)) {
                return redirect()->back()->with('error', 'Failed to save temp view.');
            }
        }

        if ($backup) {
            $backup_path = $this->tempPath . '/' . $slug . '/' . $backup;

            $cipher = file_get_contents($backup_path);
            $content = $encrypter->decrypt($cipher);

            // Optionally write decrypted to live view immediately
            file_put_contents($path, $content);

            unlink($backup_path);

            if ($slug == 'home') {
                return redirect()->to(site_url('/'));
            }
            return redirect()->to(site_url('/' . $slug));
        }

        $cipher = file_get_contents($encFile);
        $content = $encrypter->decrypt($cipher);

        // Optionally write decrypted to live view immediately
        file_put_contents($path, $content);

        unlink($encFile);

        if ($slug == 'home') {
            return redirect()->to(site_url('/'));
        }
        return redirect()->to(site_url('/' . $slug));
    }

    /**
     * Delete a specific backup
     */
    public function deleteBackup()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $slug = $this->request->getPost('slug');
        $backupFile = $this->request->getPost('backup');

        // Verify slug and backupFile
        if (!isset($this->editableViews[$slug])) {
            throw PageNotFoundException::forPageNotFound();
        }
        $filePath = $this->tempPath . '/' . $slug . '/' . basename($backupFile);
        if (!is_file($filePath)) {
            throw new FileNotFoundException("Backup file not found: {basename($backupFile)}");
        }

        unlink($filePath);
        return redirect()->to(site_url($this->adminPrefix . '/view-manager'));
    }
}
