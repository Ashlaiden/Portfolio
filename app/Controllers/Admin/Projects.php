<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\URI;
use App\Models\View;
use App\Models\Project;
use App\Models\Gallery;
use ReflectionException;

class Projects extends BaseController
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

        $projectModel = new Project();
        $projects = $projectModel->where('on_delete', false)->findAll();

        $data = [
            'meta_title' => 'Projects',
            'active' => 'projects',
            'projects' => $projects,
        ];

        return view('admin/projects', $data);
    }

    public function projectdetail($id)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $projectModel = new Project();
        $project = $projectModel->find($id);
        $galleryModel = new Gallery();
        $galleries = $galleryModel->where('project_id', $id)->findAll();

        if ($project['on_delete'] == true) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash/detail/' . $project['id']));
        }

        $data = [
            'meta_title' => 'Project Detail',
            'active' => 'projects',
            'project' => $project,
            'galleries' => $galleries,
        ];

        return view('admin/projectdetail', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function editcoverimage($id = null)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            $prjModel = new Project();
            if ($prjModel->where('on_delete', true)->find($id)) {
                return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
            }
            $data = [
                'meta_title' => 'Edit Image',
                'active' => 'projects',
                'project_id' => $id,
            ];
            return view('admin/editcoverimage', $data);
        }

        $project_id = $this->request->getPost('project_id');
        $cover_image = $this->request->getFile('cover_image');

        $validation = \Config\Services::validation();

        $rules = [
            'project_id' => 'required|is_exist[projects.id]',
            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png]|max_size[cover_image,10240]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectModel = new Project();
        $project = $projectModel->find($project_id);

        if ($project['on_delete'] === true) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
        }

        if (!$project) {
            return redirect()->to(site_url($this->adminPrefix . '/project'));
        }

        $coverImagePath = Null;
        $coverImageName = Null;

        if ($cover_image->isValid() && !$cover_image->hasMoved()) {
            // Get extension
            $extension = $cover_image->getExtension(); // or PATHINFO_EXTENSION

            // Add random number at the end with a separator
            $newName = $project['id'] . '____' . $project['slug'] . '____' . rand(1000, 9999) . '.' . $extension;

            $oldImagePath = WRITEPATH . '../public/' . $project['image'];

            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // 💥 deletes the old image
            }

            // Move to public/projects folder
            $cover_image->move('static/image/projects', $newName);

            // Save the relative path to DB
            $coverImagePath = 'static/image/projects/' . $newName;
        }

        $projectModel->update($project_id, [
            'image' => $coverImagePath,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project_id));
    }

    /**
     * @throws ReflectionException
     */
    public function newgalleryimage($id = null)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {

            $projectModeL = new Project();
            if ($projectModeL->find($id)['on_delete'] == true) {
                return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
            }

            if (!$id || !$projectModeL->find($id)) {
                return redirect()->to(site_url($this->adminPrefix . '/project'));
            }

            $data = [
                'meta_title' => 'New Gallery Image',
                'active' => 'projects',
                'project_id' => $id,
            ];

            return view('admin/newgalleryimage', $data);
        }

        $project_id = $this->request->getPost('project_id');
        $title = $this->request->getPost('title');
        $gallery_image = $this->request->getFile('gallery_image');

        $validation = \Config\Services::validation();

        $rules = [
            'project_id' => 'required|is_exist[projects.id]',
            'title' => 'required|min_length[3]|max_length[255]',
            'gallery_image' => 'uploaded[gallery_image]|is_image[gallery_image]|mime_in[gallery_image,image/jpg,image/jpeg,image/png]|max_size[gallery_image,4096]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $coverImagePath = Null;
        $coverImageName = Null;

        if ($gallery_image->isValid() && !$gallery_image->hasMoved()) {
            $gallery_image_original_name = $gallery_image->getName();
            $coverImageName = pathinfo($gallery_image_original_name, PATHINFO_FILENAME);

            // Get extension
            $extension = $gallery_image->getExtension(); // or PATHINFO_EXTENSION

            // Add random number at the end with a separator
            $newName = $coverImageName . '__Gallery__' . rand(1000, 9999) . '.' . $extension;

            // Move to public/projects folder
            $gallery_image->move('static/image/projects/' . $project_id . '/', $newName);

            // Save the relative path to DB
            $coverImagePath = 'static/image/projects/' . $project_id . '/' . $newName;
        }

        $galleryModel = new Gallery();

        $galleryModel->save([
            'project_id' => $project_id,
            'image_path' => $coverImagePath,
            'title' => $title,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project_id));
    }

    public function deletegalleryimage()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $galleryID = $this->request->getPost('id');

        $validation = \Config\Services::validation();

        $rules = [
            'id' => 'required|is_exist[gallery.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $galleryModel = new Gallery();
        $gallery = $galleryModel->find($galleryID);

        $prjModel = new Project();
        if ($prjModel->where('on_delete', true)->find($gallery['project_id'])) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
        }

        if (!$gallery) {
            return redirect()->back();
        }
        $oldImagePath = WRITEPATH . '../public/' . $gallery['image_path'];


        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);

            // Get the directory of the image
            $parentDir = dirname($oldImagePath);

            // Scan the directory
            $files = scandir($parentDir);

            // Filter out . and .. and any index.html
            $remainingFiles = array_filter($files, function ($file) {
                return $file !== '.' && $file !== '..' && $file !== 'index.html';
            });

            // If it's empty (or only has index.html), delete the folder
            if (empty($remainingFiles)) {
                // Optionally delete index.html too if you want:
                $indexPath = $parentDir . '/index.html';
                if (file_exists($indexPath)) {
                    unlink($indexPath);
                }

                rmdir($parentDir); // Delete the folder
            }
        }

        $projectID = $gallery['project_id'];
        $galleryModel->delete($galleryID);


        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $projectID));
    }

    /**
     * @throws ReflectionException
     */
    public function editgalleryimage($id = null)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {

            $galleryMODEL = new Gallery();

            $prjModel = new Project();
            if ($prjModel->where('on_delete', true)->find($galleryMODEL->find($id)['project_id'])) {
                return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
            }

            if (!$id || !$galleryMODEL->find($id)) {
                return redirect()->to(site_url($this->adminPrefix . '/project'));
            }

            $gallery = $galleryMODEL->find($id);

            $data = [
                'meta_title' => 'Edit Gallery Image',
                'active' => 'projects',
                'project_id' => $id,
                'gallery' => $gallery,
            ];

            return view('admin/editgalleryimage', $data);
        }

        $gallery_id = $this->request->getPost('gallery_id');
        $title = $this->request->getPost('title');
        $gallery_image = $this->request->getFile('gallery_image');

        $validation = \Config\Services::validation();

        $rules = [
            'gallery_id' => 'required|is_exist[gallery.id]',
            'title' => 'required|min_length[3]|max_length[255]',
            'gallery_image' => 'uploaded[gallery_image]|is_image[gallery_image]|mime_in[gallery_image,image/jpg,image/jpeg,image/png]|max_size[gallery_image,4096]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $galleryImagePath = Null;
        $galleryImageName = Null;

        $galleryMODEL = new Gallery();
        $gallery = $galleryMODEL->find($gallery_id);

        $prjModel = new Project();
        if ($prjModel->where('on_delete', true)->find($gallery['project_id'])) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
        }


        if ($gallery_image->isValid() && !$gallery_image->hasMoved()) {
            $gallery_image_original_name = $gallery_image->getName();
            $galleryImageName = pathinfo($gallery_image_original_name, PATHINFO_FILENAME);

            // Get extension
            $extension = $gallery_image->getExtension(); // or PATHINFO_EXTENSION

            // Add random number at the end with a separator
            $newName = $galleryImageName . '__Gallery__' . rand(1000, 9999) . '.' . $extension;

            // Move to public/projects folder
            $gallery_image->move('static/image/projects/' . $gallery['project_id'] . '/', $newName);

            // Save the relative path to DB
            $galleryImagePath = 'static/image/projects/' . $gallery['project_id'] . '/' . $newName;
        }

        $galleryModel = new Gallery();

        $galleryModel->update($gallery['id'], [
           'title' => $title,
           'image_path' => $galleryImagePath,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $gallery['project_id']));
    }

    /**
     * @throws ReflectionException
     */
    public function newproject()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            $data = [
                'meta_title' => 'New Project',
                'active' => 'projects',
            ];
            return view('admin/newproject', $data);
        }

        // Handle the POST
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        $programming_language = $this->request->getPost('programming_language');
        $framework = $this->request->getPost('framework');
        $description = $this->request->getPost('description');
        $cover_image = $this->request->getFile('cover_image');
        $gallery_images = $this->request->getFiles('gallery_images');

        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'slug' => 'required|alpha_dash|min_length[5]|max_length[255]|is_unique[projects.slug]',
            'programming_language' => 'required|min_length[2]|max_length[100]',
            'framework' => 'permit_empty|max_length[100]',
            'description' => 'required|min_length[10]',
            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png]|max_size[cover_image,10240]',
            'gallery_images.*' => 'permit_empty|is_image[gallery_images]|mime_in[gallery_images,image/jpg,image/jpeg,image/png]|max_size[gallery_images,4096]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $coverImagePath = Null;
        $coverImageName = Null;

        $projectModel = new Project();

        $projectModel->save([
            'title' => $title,
            'slug' => $slug,
            'programming_language' => $programming_language,
            'framework' => $framework,
            'description' => $description,
        ]);

        $projectId = $projectModel->getInsertID();
        $project = $projectModel->find($projectId);

        if ($cover_image->isValid() && !$cover_image->hasMoved()) {
            // Get extension
            $extension = $cover_image->getExtension(); // or PATHINFO_EXTENSION

            // Add random number at the end with a separator
            $newName = $project['id'] . '____' . $project['slug'] . '____' . rand(1000, 9999) . '.' . $extension;

            // Move to public/projects folder
            $cover_image->move('static/image/projects', $newName);

            // Save the relative path to DB
            $coverImagePath = 'static/image/projects/' . $newName;
        }

        $projectModel->update($projectId, [
            'image' => $coverImagePath
        ]);

        var_dump($gallery_images);

        foreach ($gallery_images['gallery_images'] as $image) {
            if ($image->isValid() && !$image->hasMoved()) {
                $gallery_image_original_name = $image->getName();
                $galleryImageName = pathinfo($gallery_image_original_name, PATHINFO_FILENAME);

                // Get extension
                $extension = $image->getExtension(); // or PATHINFO_EXTENSION

                // Add random number at the end with a separator
                $newName = $galleryImageName . '__Gallery__' . rand(1000, 9999) . '.' . $extension;

                // Move to public/projects folder
                $image->move('static/image/projects/' . $projectId . '/', $newName);

                // Save the relative path to DB
                $galleryImagePath = 'static/image/projects/' . $projectId . '/' . $newName;

                $galleryModel = new Gallery();

                $galleryModel->save([
                    'project_id' => $projectId,
                    'image_path' => $galleryImagePath,
                    'title' => $galleryImageName,
                ]);

            }
        }

        return redirect()->to(site_url($this->adminPrefix . '/dashboard'));

    }

    /**
     * @throws ReflectionException
     */
    public function editproject($id = null)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            $projectModel = new Project();
            $project = $projectModel->find($id);

            if ($project['on_delete'] == true) {
                return redirect()->to(site_url($this->adminPrefix . '/project/trash/detail/' . $project['id']));
            }

            $data = [
                'meta_title' => 'Edit Project',
                'active' => 'projects',
                'project' => $project,
            ];

            return view('admin/editproject', $data);
        }

        // Handle the POST
        $projectID = $this->request->getPost('id');
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        $programming_language = $this->request->getPost('programming_language');
        $framework = $this->request->getPost('framework');
        $description = $this->request->getPost('description');
        //        $cover_image = $this->request->getFile('cover_image');
//        $gallery_images = $this->request->getFile('gallery_images');

        $validation = \Config\Services::validation();

        $rules = [
            'id' => 'required|is_exist[projects.id]',
            'title' => 'required|min_length[5]|max_length[255]',
            'slug' => 'required|alpha_dash|min_length[5]|max_length[255]|is_unique[projects.slug,id,{id}]',
            'programming_language' => 'required|min_length[2]|max_length[100]',
            'framework' => 'permit_empty|max_length[100]',
            'description' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectModel = new Project();

        if ($projectModel->find($projectID)['on_delete'] == true) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash/detail/' . $projectID));
        }

        $projectModel->update($projectID, [
            'title' => $title,
            'slug' => $slug,
            'programming_language' => $programming_language,
            'framework' => $framework,
            'description' => $description,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $projectID));

    }

    /**
     * @throws ReflectionException
     */
    public function deleteproject()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $projectID = $this->request->getPost('project_id');

        $validation = \Config\Services::validation();

        $rules = [
            'project_id' => 'required|is_exist[projects.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectModel = new Project();
        $project = $projectModel->find($projectID);

        if (!$project) {
            return redirect()->back();
        }

        if ($project['on_delete'] === true) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
        }

        $projectModel->update($project['id'], [
            'on_delete' => true,
            'deleted_at' => time(),
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/'));
    }

    public function trash()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }
        
        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $projectModel = new Project();
        $projects = $projectModel->where('on_delete', true)->findAll();

        $data = [
            'meta_title' => 'Projects',
            'active' => 'projects',
            'trashes' => $projects,
        ];

        return view('admin/trash', $data);
    }

    public function trashdetail($id)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $projectModel = new Project();
        $project = $projectModel->find($id);
        $galleryModel = new Gallery();
        $galleries = $galleryModel->where('project_id', $id)->findAll();

        if ($project['on_delete'] == false) {
            return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project['id']));
        }

        $data = [
            'meta_title' => 'Project Detail',
            'active' => 'projects',
            'project' => $project,
            'galleries' => $galleries,
        ];

        return view('admin/trashdetail', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function survivetrashproject()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $projectID = $this->request->getPost('project_id');

        $validation = \Config\Services::validation();

        $rules = [
            'project_id' => 'required|is_exist[projects.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectModel = new Project();
        $project = $projectModel->find($projectID);

        if (!$project) {
            return redirect()->back();
        }

        if ($project['on_delete'] == false) {
            return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project['id']));
        }

        $projectModel->update($project['id'], [
            'on_delete' => false,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/project/'));
    }

    protected function deleteFolder($folderPath)
    {
        // Check if path exists and is a directory
        if (!is_dir($folderPath)) {
            return false;
        }

        // Scan all items inside the folder
        $files = array_diff(scandir($folderPath), ['.', '..']);

        foreach ($files as $file) {
            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath)) {
                // Recursively delete subfolder
                deleteFolder($fullPath);
            } else {
                // Delete file
                unlink($fullPath);
            }
        }

        // Remove the now-empty folder
        return rmdir($folderPath);
    }

    public function removetrashproject() {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (! in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $projectID = $this->request->getPost('project_id');

        $validation = \Config\Services::validation();

        $rules = [
            'project_id' => 'required|is_exist[projects.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectModel = new Project();
        $project = $projectModel->find($projectID);

        if (!$project) {
            return redirect()->back();
        }

        if ($project['on_delete'] == false) {
            return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project['id']));
        }

        $oldImagePath = WRITEPATH . '../public/' . $project['image'];

        if (file_exists($oldImagePath)) {
            unlink($oldImagePath); // deletes the old image
        }

        $folderpath = WRITEPATH . '../public/static/image/projects/' . $project['id'];
        $this->deleteFolder($folderpath);

        $projectModel->delete($project['id']);

        echo '<h1>Hello</h1>';

        return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
    }
}












