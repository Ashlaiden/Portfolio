<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GalleryProject;
use App\Models\Project;
use App\Models\Gallery as GalleryModel;
use ReflectionException;

class Gallery extends BaseController
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

        $galleryModel = new GalleryModel();
        $galleries = $galleryModel->where('on_delete', false)->findAll();

        $data = [
            'meta_title' => 'Projects',
            'active' => 'gallery',
            'gallery' => $galleries,
        ];

        return view('admin/gallery', $data);
    }

    public function gallerydetail($id)
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        $galleryModel = new GalleryModel();
        $gallery = $galleryModel->find($id);

        if ($gallery['on_delete'] == true) {
            return redirect()->to(site_url($this->adminPrefix . '/project/trash/detail/' . $project['id']));
        }

        $projects = $galleryModel->getProjects($gallery['id']);

        $data = [
            'meta_title' => 'Project Detail',
            'active' => 'gallery',
            'gallery' => $gallery,
            'projects' => $projects,
        ];

        return view('admin/gallerydetail', $data);
    }

//    /**
//     * @throws ReflectionException
//     */
//    public function editcoverimage($id = null)
//    {
//        if (!session('isAdmin')) {
//            return redirect()->to(site_url($this->adminPrefix . '/login'));
//        }
//
//        if ($this->request->getMethod() !== 'POST') {
//            $prjModel = new Project();
//            if ($prjModel->where('on_delete', true)->find($id)) {
//                return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
//            }
//            $data = [
//                'meta_title' => 'Edit Image',
//                'active' => 'projects',
//                'project_id' => $id,
//            ];
//            return view('admin/editcoverimage', $data);
//        }
//
//        $project_id = $this->request->getPost('project_id');
//        $cover_image = $this->request->getFile('cover_image');
//
//        $validation = \Config\Services::validation();
//
//        $rules = [
//            'project_id' => 'required|is_exist[projects.id]',
//            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png]|max_size[cover_image,10240]',
//        ];
//
//        if (!$this->validate($rules)) {
//            // If validation fails, redirect back with input and errors
//            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
//        }
//
//        $projectModel = new Project();
//        $project = $projectModel->find($project_id);
//
//        if ($project['on_delete'] === true) {
//            return redirect()->to(site_url($this->adminPrefix . '/project/trash'));
//        }
//
//        if (!$project) {
//            return redirect()->to(site_url($this->adminPrefix . '/project'));
//        }
//
//        $coverImagePath = Null;
//        $coverImageName = Null;
//
//        if ($cover_image->isValid() && !$cover_image->hasMoved()) {
//            // Get extension
//            $extension = $cover_image->getExtension(); // or PATHINFO_EXTENSION
//
//            // Add random number at the end with a separator
//            $newName = $project['id'] . '____' . $project['slug'] . '____' . rand(1000, 9999) . '.' . $extension;
//
//            $oldImagePath = WRITEPATH . '../public/' . $project['image'];
//
//            if (file_exists($oldImagePath)) {
//                unlink($oldImagePath); // 💥 deletes the old image
//            }
//
//            // Move to public/projects folder
//            $cover_image->move('static/image/projects', $newName);
//
//            // Save the relative path to DB
//            $coverImagePath = 'static/image/projects/' . $newName;
//        }
//
//        $projectModel->update($project_id, [
//            'image' => $coverImagePath,
//        ]);
//
//        return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project_id));
//    }

    /**
     * @throws ReflectionException
     */
    public function newGallery()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {

            $projectModel = new Project();
            $projects = $projectModel->where('on_delete', false)->findAll();

            $data = [
                'meta_title' => 'New Gallery Image',
                'active' => 'gallery',
                'project_id' => null,
                'submit' => admin_url('gallery/new-gallery'),
                'projects' => $projects,
            ];

            $projectID = $this->request->getGet('prjID');
            if ($projectID) {
                $data['project_id'] = $projectID;
            }

            return view('admin/newgalleryimage', $data);
        }

        $title = $this->request->getPost('title');
        $gallery_image = $this->request->getFile('gallery_image');
        $project_select = $this->request->getFile('project_Select');

        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'gallery_image' => 'uploaded[gallery_image]|is_image[gallery_image]|mime_in[gallery_image,image/jpg,image/jpeg,image/png]|max_size[gallery_image,4096]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ImagePath = Null;
        $ImageName = Null;

        if ($gallery_image->isValid() && !$gallery_image->hasMoved()) {
            $gallery_image_original_name = $gallery_image->getName();
            $ImageName = pathinfo($gallery_image_original_name, PATHINFO_FILENAME);

            // Get extension
            $extension = $gallery_image->getExtension(); // or PATHINFO_EXTENSION

            // Add random number at the end with a separator
            $newName = $ImageName . '__Gallery__' . rand(1000, 9999) . '.' . $extension;

            // Move to public/projects folder
            $gallery_image->move('static/image/gallery/', $newName);

            // Save the relative path to DB
            $ImagePath = 'static/image/gallery/' . $newName;
        }

        $galleryModel = new GalleryModel();

        $dataModel = [
            'image_path' => $ImagePath,
            'title' => $title,
        ];
        $galleryModel->insert($dataModel);
        $galleryID = $galleryModel->getInsertID();

        $projectModel = new Project();
        if ($project_select !== 'none' && $projectModel->find($project_select)) {
            $galleryModel->addProject($galleryID, $project_select);
        }


        return redirect()->to(site_url($this->adminPrefix . '/gallery'));
    }

    /**
     * @throws ReflectionException
     */
    public function deletegalleryimage()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $galleryID = $this->request->getPost('gallery_id');

        $validation = \Config\Services::validation();

        $rules = [
            'gallery_id' => 'required|is_exist[gallery.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $galleryModel = new GalleryModel();
        $gallery = $galleryModel->find($galleryID);
        if (!$gallery) {
            return redirect()->back();
        }

        if ($gallery['on_delete']) {
            return redirect()->to(site_url($this->adminPrefix . '/gallery/trash'));
        }

        $galleryModel->update($gallery['id'], [
            'on_delete' => true,
            'deleted_at' => date("Y-m-d H:i:s", time())
        ]);


        return redirect()->to(site_url($this->adminPrefix . '/gallery'));
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

            $galleryMODEL = new GalleryModel();
            if (!$id || !$galleryMODEL->find($id)) {
                return redirect()->to(site_url($this->adminPrefix . '/gallery'));
            }
            if ($galleryMODEL->where('on_delete', true)->find($id)) {
                return redirect()->to(site_url($this->adminPrefix . '/gallery/trash'));
            }

            $gallery = $galleryMODEL->find($id);
            $projectModel = new Project();
            $projects = $projectModel->where('on_delete', false)->findAll();
            $projectID = $galleryMODEL->getProjects($gallery['id']);

            $data = [
                'meta_title' => 'Edit Gallery Image',
                'active' => 'projects',
                'gallery' => $gallery,
                'projects' => $projects,
            ];

            if ($projectID) {
                $data['project_id'] = $projectID;
            } else {
                $data['project_id'] = 'none';
            }

            return view('admin/editgalleryimage', $data);
        }

        $gallery_id = $this->request->getPost('gallery_id');
        $title = $this->request->getPost('title');
        $gallery_image = $this->request->getFile('gallery_image');
        $project_select = $this->request->getPost('project_select');

        $validation = \Config\Services::validation();

        $rules = [
            'gallery_id' => 'required|is_exist[gallery.id]',
            'title' => 'required|min_length[3]|max_length[255]',
            'gallery_image' => 'permit_empty|is_image[gallery_image]|mime_in[gallery_image,image/jpg,image/jpeg,image/png]|max_size[gallery_image,4096]',
        ];

        if ($project_select !== "none") {
            $rules['project_Select'] = 'is_exist[projects.id]';
        }

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $galleryMODEL = new GalleryModel();
        $gallery = $galleryMODEL->find($gallery_id);

        $GMData = [
            'title' => $title,
        ];

        if ($gallery_image) {
            $galleryImagePath = Null;
            $galleryImageName = Null;

            if ($gallery_image->isValid() && !$gallery_image->hasMoved()) {
                $gallery_image_original_name = $gallery_image->getName();
                $galleryImageName = pathinfo($gallery_image_original_name, PATHINFO_FILENAME);

                // Get extension
                $extension = $gallery_image->getExtension(); // or PATHINFO_EXTENSION

                // Add random number at the end with a separator
                $newName = $galleryImageName . '__Gallery__' . rand(1000, 9999) . '.' . $extension;

                // Move to public/projects folder
                $gallery_image->move('static/image/gallery/' , $newName);

                // Save the relative path to DB
                $galleryImagePath = 'static/image/gallery/' . $newName;
                $GMData['image_path'] = $galleryImagePath;
            }
        }

        $galleryMODEL->update($gallery['id'], $GMData);

        $projectModel = new Project();
        if ($project_select !== 'none' && $projectModel->find($project_select)) {
            $project = $galleryMODEL->getProjects($gallery['id']);
            if ($project['id'] !== $project_select && $project['on_delete'] !== true) {
                $galleryMODEL->removeProject($gallery['id'], $project['id']);
                $galleryMODEL->addProject($gallery['id'], $project_select);
            }
        }

        return redirect()->to(site_url($this->adminPrefix . '/gallery/detail/' . $gallery['id']));
    }

    public function trash()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (!in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        $galleryModel = new GalleryModel();
        $galleries = $galleryModel->where('on_delete', true)->findAll();

        $data = [
            'meta_title' => 'Projects',
            'active' => 'gallery',
            'trashes' => $galleries,
        ];

        return view('admin/gallerytrash', $data);
    }

//    public function trashdetail($id)
//    {
//        if (!session('isAdmin')) {
//            return redirect()->to(site_url($this->adminPrefix . '/login'));
//        }
//
//        if (!in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
//            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
//        }
//
//        $projectModel = new Project();
//        $project = $projectModel->find($id);
//        $galleryModel = new Gallery();
//        $galleries = $galleryModel->where('project_id', $id)->findAll();
//
//        if ($project['on_delete'] == false) {
//            return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project['id']));
//        }
//
//        $data = [
//            'meta_title' => 'Project Detail',
//            'active' => 'projects',
//            'project' => $project,
//            'galleries' => $galleries,
//        ];
//
//        return view('admin/trashdetail', $data);
//    }

    /**
     * @throws ReflectionException
     */
    public function survivetrashgallery()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (!in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $galleryID = $this->request->getPost('gallery_id');

        $validation = \Config\Services::validation();

        $rules = [
            'gallery_id' => 'required|is_exist[gallery.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $galleryModel = new GalleryModel();
        $gallery = $galleryModel->find($galleryID);

        if (!$gallery) {
            return redirect()->back();
        }

        if ($gallery['on_delete'] == false) {
            return redirect()->to(site_url($this->adminPrefix . '/project/detail/' . $project['id']));
        }

        $galleryModel->update($gallery['id'], [
            'on_delete' => false,
        ]);

        return redirect()->to(site_url($this->adminPrefix . '/gallery/trash'));
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

    public function removetrashgallery()
    {
        if (!session('isAdmin')) {
            return redirect()->to(site_url($this->adminPrefix . '/login'));
        }

        if (!in_array(session()->get('admin_role'), ['owner', 'superadmin'])) {
            return view('admin/accessdenied', ['meta_title' => 'Access Denied', 'active' => 'setting']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $galleryID = $this->request->getPost('gallery_id');

        $validation = \Config\Services::validation();

        $rules = [
            'gallery_id' => 'required|is_exist[gallery.id]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $galleryModel = new GalleryModel();
        $gallery = $galleryModel->find($galleryID);

        if (!$gallery) {
            return redirect()->back();
        }

        if (!$gallery['on_delete']) {
            return redirect()->to(site_url($this->adminPrefix . '/gallery/detail/' . $gallery['id']));
        }

//        $ImagePath = WRITEPATH . '../public/' . $gallery['image_path'];
        $ImagePath = FCPATH . $gallery['image_path'];

//        if (file_exists($ImagePath)) {
//            if (!unlink($ImagePath)) {
//                log_message('error', 'Failed to delete image at: ' . $ImagePath);
//            } else {
//                echo('Deleted: ' . $ImagePath);
//            }
//        } else {
//            log_message('error', 'Image path not found: ' . $ImagePath);
//        }


        if (file_exists($ImagePath)) {
            unlink($ImagePath); // deletes the image file
        }

        $galleryModel->delete($gallery['id']);


        return redirect()->to(site_url($this->adminPrefix . '/gallery/trash'));
    }
}



//$oldImagePath = WRITEPATH . '../public/' . $gallery['image_path'];
//
//
//if (file_exists($oldImagePath)) {
//    unlink($oldImagePath);
//
//    // Get the directory of the image
//    $parentDir = dirname($oldImagePath);
//
//    // Scan the directory
//    $files = scandir($parentDir);
//
//    // Filter out . and .. and any index.html
//    $remainingFiles = array_filter($files, function ($file) {
//        return $file !== '.' && $file !== '..' && $file !== 'index.html';
//    });
//
//    // If it's empty (or only has index.html), delete the folder
//    if (empty($remainingFiles)) {
//        // Optionally delete index.html too if you want:
//        $indexPath = $parentDir . '/index.html';
//        if (file_exists($indexPath)) {
//            unlink($indexPath);
//        }
//
//        rmdir($parentDir); // Delete the folder
//    }
//}










