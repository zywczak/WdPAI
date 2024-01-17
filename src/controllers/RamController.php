<?php

require_once 'ProductController.php';  // Assuming ProductController is already defined
require_once __DIR__ . '/../models/RAM.php';  // Assuming RAM model is already defined
require_once __DIR__ . '/../repository/RamRepository.php';

class RamController extends ProductController
{
    private $ramRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ramRepository = new RamRepository();
    }

    public function rams()
    {
        $rams = $this->ramRepository->getAllRams();
        $this->render('rams', ['rams' => $rams]);
    }

    public function addRam()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle ram addition logic based on the form submission
            $id = null;
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];
            $speed = $_POST['speed'];
            $capacity = $_POST['capacity'];
            $voltage = $_POST['voltage'];
            $moduleCount = $_POST['module_count'];
            $backlight = isset($_POST['backlight']) ? true : 0;
            $cooling = isset($_POST['cooling']) ? true : 0;
            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams,  'messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new RAM instance
            $ram = new Ram($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            // Add the RAM to the repository
            $this->ramRepository->addRam($ram);

            $rams = $this->ramRepository->getAllRams();
            return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Ram added successfully']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling)
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

        if (!is_string($capacity)) {
            $errors[] = 'Capacity must be a string.';
        }

        if (!ctype_digit($moduleCount) || $moduleCount < 0) {
            $errors[] = 'Module count must be an integer.';
        }

        if (!is_numeric($speed) || $speed <= 0) {
            $errors[] = 'Speed must be a positive number.';
        }

        if (!is_numeric($voltage) || $voltage <= 0) {
            $errors[] = 'Voltage must be a positive number.';
        }

        return $errors;
    }

    public function ramsEdit()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /rams'); // Adjust the path accordingly
        exit();
    }

    $rams = $this->ramRepository->getAllRams();
    $this->render('ramsEdit', ['rams' => $rams]);
}

    public function deleteRam()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isGet()) {
        // Get the ram ID from the query parameters
        $ramId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validate the ID
        if ($ramId === false || $ramId <= 0) {
            // Invalid ID, handle accordingly (show error message, redirect, etc.)
            // For now, redirect to the ramsEdit page with an error message
            header('Location: /ramsEdit?messages=invalid_id');
            exit();
        }

        try {
            // Call the repository method to delete the ram
            $success = $this->ramRepository->deleteRam($ramId);

            if ($success) {
                // Product deleted successfully, redirect to the ramsEdit page with a success message
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Ram deleted successfully']]);
            } else {
                // Product deletion failed, redirect to the ramsEdit page with an error message
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Failed to delete ram']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the ramsEdit page with an error message
            $rams = $this->ramRepository->getAllRams();
            return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Error deleting ram']]);
        }
    }
}

public function updateRam()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle ram addition logic based on the form submission
            $id = $_POST['id'];
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];
            $speed = $_POST['speed'];
            $capacity = $_POST['capacity'];
            $voltage = $_POST['voltage'];
            $moduleCount = $_POST['module_count'];
            $backlight = isset($_POST['backlight']) ? true : 0;
            $cooling = isset($_POST['cooling']) ? true : 0;
            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                return $this->render('ramsEdit', ['messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new RAM instance
            $ram = new Ram($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            // Add the RAM to the repository
            $this->ramRepository->updateRam($ram);

            $rams = $this->ramRepository->getAllRams();
            return $this->render('ramssEdit', ['rams' => $rams, 'messages' => ['Ram updated successfully']]);
        }
    }

}