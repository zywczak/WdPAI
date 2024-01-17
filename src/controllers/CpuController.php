<?php

require_once 'ProductController.php';  // Assuming ProductController is already defined
require_once __DIR__ . '/../models/CPU.php';  // Assuming CPU model is already defined
require_once __DIR__ . '/../repository/CpuRepository.php';

class CpuController extends ProductController
{
    private $cpuRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cpuRepository = new CpuRepository();
    }

    public function cpus()
    {
        
        $cpus = $this->cpuRepository->getAllCpus();
        $this->render('cpus', ['cpus' => $cpus]);
    }

    public function addCpu()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle cpu addition logic based on the form submission
            $id = null;
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];

            $speed = $_POST['speed'];
            $architecture = $_POST['architecture'];
            $supportedMemory = $_POST['supported_memory'];
            $cooling = isset($_POST['cooling']) ? true : 0;
            $threads = $_POST['threads'];
            $technologicalProcess = $_POST['technological_process'];
            $powerConsumption = $_POST['power_consumption'];

            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption );

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new cpu instance
            $cpu = new Cpu($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption);

            // Add the cpu to the repository
            $this->cpuRepository->addCpu($cpu);

            $cpus = $this->cpuRepository->getAllCpus();
            return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu added successfully']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption )
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

        if (!is_numeric($speed) || $speed <= 0) {
            $errors[] = 'Speed must be a positive number.';
        }

        if (empty($architecture)) {
            $errors[] = 'Architekture is required.';
        }

        if (empty($supportedMemory)) {
            $errors[] = 'Supported memory is required.';
        }

        if (!ctype_digit($threads) || $threads < 0) {
            $errors[] = 'Threads must be an integer.';
        }

        if (!ctype_digit($technologicalProcess) || $technologicalProcess < 0) {
            $errors[] = 'Technological process must be an integer.';
        }

        if (!ctype_digit($powerConsumption) || $powerConsumption < 0) {
            $errors[] = 'Power consumption must be an integer.';
        }

        return $errors;
    }

    public function cpusEdit()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /cpus'); // Adjust the path accordingly
        exit();
    }

    $cpus = $this->cpuRepository->getAllCpus();
    $this->render('cpusEdit', ['cpus' => $cpus]);
}

    public function deleteCpu()
{
    // Check if user is logged in and is an admin
    if ($_SESSION['user_type'] !== 'admin') {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isGet()) {
        // Get the cpu ID from the query parameters
        $cpuId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validate the ID
        if ($cpuId === false || $cpuId <= 0) {
            // Invalid ID, handle accordingly (show error message, redirect, etc.)
            // For now, redirect to the cpusEdit page with an error message
            header('Location: /cpusEdit?messages=invalid_id');
            exit();
        }

        try {
            // Call the repository method to delete the cpu
            $success = $this->cpuRepository->deleteCpu($cpuId);

            if ($success) {
                // Product deleted successfully, redirect to the cpusEdit page with a success message
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu deleted successfully']]);
            } else {
                // Product deletion failed, redirect to the cpusEdit page with an error message
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Failed to delete cpu']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the cpusEdit page with an error message
            $cpus = $this->cpuRepository->getAllCpus();
            return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Error deleting cpu']]);
        }
    }
}

public function updateCpu()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle cpu addition logic based on the form submission
            $id = $_POST['id'];
            $manufacture = $_POST['manufacture'];
            $model = $_POST['model'];
            $price = $_POST['price'];

            $speed = $_POST['speed'];
            $architecture = $_POST['architecture'];
            $supportedMemory = $_POST['supported_memory'];
            $cooling = isset($_POST['cooling']) ? true : 0;
            $threads = $_POST['threads'];
            $technologicalProcess = $_POST['technological_process'];
            $powerConsumption = $_POST['power_consumption'];

            // Validate form data
            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption );

            if (!empty($errors)) {
                // If there are validation errors, render the form again with error messages
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => $errors]);
            }

            // Validate and handle file upload
            $photo = $this->handleFileUpload();

            // Create a new cpu instance
            $cpu = new Cpu($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption);

            // Add the cpu to the repository
            $this->cpuRepository->updateCpu($cpu);

            $cpus = $this->cpuRepository->getAllCpus();
            return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu updated successfully']]);
        }
    }
}