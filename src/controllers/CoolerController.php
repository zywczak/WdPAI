<?php

require_once 'ProductController.php';
require_once __DIR__ . '/../models/Cooler.php';
require_once __DIR__ . '/../repository/CoolerRepository.php';

class CoolerController extends ProductController
{
    private $coolerRepository;
    const MAX_FILE_SIZE = 1024 * 1024; // 1 MB
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../../public/img/';

    public function __construct()
    {
        parent::__construct();
        $this->coolerRepository = new CoolerRepository();
    }

    public function coolers()
    {
        $coolers = $this->coolerRepository->getAllCoolers();
        $this->render('coolers', ['coolers' => $coolers]);
    }

    public function addCooler()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle Cooler addition logic based on the form submission
            $id = null;
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];
            $type = $_POST['type'];
            $fanCount = $_POST['fan_count'];
            $fanSize = $_POST['fan_size'];
            $backlight = isset($_POST['backlight']) ? true : 0;
            $material = $_POST['material'];
            $radiatorSize = $_POST['radiator_size'];
            $compatibility = $_POST['compatibility'];
            error_log($backlight);
            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility);

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                return $this->render('coolersEdit', ['messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new Cooler instance
            $cooler = new Cooler($id, $manufacture, $model, $price, $photo, $type, $fanCount, $fanSize, $backlight, $material, $radiatorSize, $compatibility);

            // Add the Cooler to the repository
            $this->coolerRepository->addCooler($cooler);

            $coolers = $this->coolerRepository->getAllCoolers();
            return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler added successfully']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility)
    {
        $errors = [];

        if (empty($manufacture)) {
            $errors[] = 'Manufacturer is required.';
        }

        if (empty($model)) {
            $errors[] = 'Model is required.';
        }

        if (!is_numeric($price) || $price <= 0) {
            $errors[] = 'Price must be a positive number.';
        }

        if (!is_string($type)) {
            $errors[] = 'Type must be a string.';
        }

        if (!ctype_digit($fanCount) || $fanCount < 0) {
            $errors[] = 'Fan Count must be an integer.';
        }

        if (!ctype_digit($fanSize) || $fanSize < 0) {
            $errors[] = 'Fan Size must be an integer.';
        }

        if (empty($material)) {
            $errors[] = 'Material is required.';
        }

        if (empty($radiatorSize)) {
            $errors[] = 'Radiator Size is required';
        }

        if (empty($compatibility)) {
            $errors[] = 'Compatibility is required';
        }

        return $errors;
    }

    private function handleFileUpload()
    {
        $file = $_FILES['photo'];

        // Check if the file is an image (JPEG or PNG)
        if (!in_array($file['type'], self::SUPPORTED_TYPES)) {
            // Invalid file type, handle accordingly (show error message, redirect, etc.)
            return null;
        }

        $uploadPath = __DIR__ . self::UPLOAD_DIRECTORY;
        $photo = $file['name'];

        // Save uploaded file to a folder
        move_uploaded_file($file['tmp_name'], $uploadPath . $photo);

        return $photo;
    }

    public function coolersEdit()
    {
        // Check if user is logged in and is an admin
        if ($_SESSION['user_type'] !== 'admin') {
            // Redirect to the unauthorized page if not an admin
            header('Location: /coolers'); // Adjust the path accordingly
            exit();
        }

        $coolers = $this->coolerRepository->getAllCoolers();
        $this->render('coolersEdit', ['coolers' => $coolers]);
    }

    public function deleteCooler()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isGet()) {
        // Get the cooler ID from the query parameters
        $coolerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validate the ID
        if ($coolerId === false || $coolerId <= 0) {
            // Invalid ID, handle accordingly (show error message, redirect, etc.)
            // For now, redirect to the coolersEdit page with an error message
            header('Location: /coolersEdit?messages=invalid_id');
            exit();
        }

        try {
            // Call the repository method to delete the cooler
            $success = $this->coolerRepository->deleteCooler($coolerId);

            if ($success) {
                // Product deleted successfully, redirect to the coolersEdit page with a success message
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler deleted successfully']]);
            } else {
                // Product deletion failed, redirect to the coolersEdit page with an error message
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Failed to delete cooler']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the coolersEdit page with an error message
            $coolers = $this->coolerRepository->getAllCoolers();
            return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Error deleting cooler']]);
        }
    }
}

public function updateCooler()
{
    // Check if user is logged in
    if (!isset($_SESSION['user_ID'])) {
        // Redirect to the login page if not logged in
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isPost()) {
        // Handle Cooler update logic based on the form submission
        $id = $_POST['id'];
        $manufacture = $_POST['manufacture'];
        $model = $_POST['model'];
        $price = $_POST['price'];
        $type = $_POST['type'];
        $fanCount = $_POST['fan_count'];
        $fanSize = $_POST['fan_size'];
        $backlight = isset($_POST['backlight']) ? true : 0;
        $material = $_POST['material'];
        $radiatorSize = $_POST['radiator_size'];
        $compatibility = $_POST['compatibility'];

        // Validate form data
        $errors = $this->validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility);

        if (!empty($errors)) {
            // If there are validation errors, render the form again with error messages
            return $this->render('coolersEdit', ['messages' => $errors]);
        }

        // Validate and handle file upload
        $photo = $this->handleFileUpload();

        // Create a new Cooler instance
        $cooler = new Cooler($id, $manufacture, $model, $price, $photo, $type, $fanCount, $fanSize, $backlight, $material, $radiatorSize, $compatibility);

        // Update the Cooler in the repository
        $this->coolerRepository->updateCooler($cooler);

        $coolers = $this->coolerRepository->getAllCoolers();
        return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler updated successfully']]);
    }
}



}
