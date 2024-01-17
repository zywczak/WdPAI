<?php

require_once 'ProductController.php';  // Assuming ProductController is already defined
require_once __DIR__ . '/../models/Motherboard.php';  // Assuming Motherboard model is already defined
require_once __DIR__ . '/../repository/MotherboardRepository.php';

class MotherboardController extends ProductController
{
    private $motherboardRepository;

    public function __construct()
    {
        parent::__construct();
        $this->motherboardRepository = new MotherboardRepository();
    }

    public function motherboards()
    {
        
        $motherboards = $this->motherboardRepository->getAllMotherboards();
        $this->render('motherboards', ['motherboards' => $motherboards]);
    }

    public function addMotherboard()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle mot$motherboard addition logic based on the form submission
            $id = null;
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];

            $chipset = $_POST['chipset'];
            $formFactor = $_POST['form_factor'];
            $supportedMemory = $_POST['supported_memory'];
            $socket = $_POST['socket'];
            $cpuArchitecture = $_POST['cpu_architecture'];
            $internalConnectors = $_POST['internal_connectors'];
            $externalConnectors = $_POST['external_connectors'];
            $memorySlots = $_POST['memory_slots'];
            $audioSystem = $_POST['audio_system'];

            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem );

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new motherboard instance
            $motherboard = new Motherboard($id, $manufacture, $model, $price, $photo, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem);

            // Add the motherboard to the repository
            $this->motherboardRepository->addMotherboard($motherboard);

            $motherboards = $this->motherboardRepository->getAllMotherboards();
            return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard added successfully']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem)
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

        if (empty($chipset)) {
            $errors[] = 'Chipset is required.';
        }
        
        if (!is_string($formFactor)) {
            $errors[] = 'Form factor must be a string.';
        }

        if (!is_string($supportedMemory)) {
            $errors[] = 'Supported memory must be a string.';
        }

        if (empty($socket)) {
            $errors[] = 'Socket is required.';
        }

        if (empty($cpuArchitecture)) {
            $errors[] = 'Cpu architecture is required.';
        }

        if (empty($internalConnectors)) {
            $errors[] = 'Internal Connectors are required.';
        }

        if (empty($externalConnectors)) {
            $errors[] = 'External connectors is required.';
        }

        if (!is_string($audioSystem)) {
            $errors[] = 'Audio system must be a string.';
        }

        if (!ctype_digit($memorySlots) || $memorySlots < 0) {
            $errors[] = 'Memory slots must be an integer.';
        }

        return $errors;
    }

    public function motherboardsEdit()
    {
        // Check if user is logged in and is an admin
        if ($_SESSION['user_type'] !== 'admin') {
            // Redirect to the unauthorized page if not an admin
            header('Location: /motherboards'); // Adjust the path accordingly
            exit();
        }
    
        $motherboards = $this->motherboardRepository->getAllmotherboards();
        $this->render('motherboardsEdit', ['motherboards' => $motherboards]);
    }

    public function deleteMotherboard()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isGet()) {
        // Get the mot$motherboard ID from the query parameters
        $motherboardId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validate the ID
        if ($motherboardId === false || $motherboardId <= 0) {
            // Invalid ID, handle accordingly (show error message, redirect, etc.)
            // For now, redirect to the motherboardsEdit page with an error message
            header('Location: /motherboardsEdit?messages=invalid_id');
            exit();
        }

        try {
            // Call the repository method to delete the mot$motherboard
            $success = $this->motherboardRepository->deleteMotherboard($motherboardId);

            if ($success) {
                // Product deleted successfully, redirect to the motherboardsEdit page with a success message
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Motherboard deleted successfully']]);
            } else {
                // Product deletion failed, redirect to the motherboardsEdit page with an error message
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Failed to delete motherboard']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the motherboardsEdit page with an error message
            $motherboards = $this->motherboardRepository->getAllMotherboards();
            return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Error deleting motherboard']]);
        }
    }
}

public function updateMotherboard()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle mot$motherboard addition logic based on the form submission
            $id = $_POST['id'];
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];

            $chipset = $_POST['chipset'];
            $formFactor = $_POST['form_factor'];
            $supportedMemory = $_POST['supported_memory'];
            $socket = $_POST['socket'];
            $cpuArchitecture = $_POST['cpu_architecture'];
            $internalConnectors = $_POST['internal_connectors'];
            $externalConnectors = $_POST['external_connectors'];
            $memorySlots = $_POST['memory_slots'];
            $audioSystem = $_POST['audio_system'];

            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem );

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                return $this->render('motherboardsEdit', ['messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new motherboard instance
            $motherboard = new Motherboard($id, $manufacture, $model, $price, $photo, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem);

            // Add the motherboard to the repository
            $this->motherboardRepository->updateMotherboard($motherboard);

            $motherboards = $this->motherboardRepository->getAllMotherboards();
            return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard updated successfully']]);
        }
    }
    
}
