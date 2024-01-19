<?php

require_once 'ProductController.php';
require_once __DIR__ . '/../models/Motherboard.php';
require_once __DIR__ . '/../repository/MotherboardRepository.php';

class MotherboardController extends ProductController{
    private $motherboardRepository;

    public function __construct(){
        parent::__construct();
        $this->motherboardRepository = new MotherboardRepository();
    }

    public function motherboards(){
        $motherboards = $this->motherboardRepository->getAllMotherboards();
        $this->render('motherboards', ['motherboards' => $motherboards]);
    }

    public function addMotherboard(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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

            $errors = $this->validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem );

            if (!empty($errors)) {
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $motherboard = new Motherboard($id, $manufacture, $model, $price, $photo, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem);

            if(!$this->motherboardRepository->addMotherboard($motherboard)){
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard added successfully']]);
            }

            $motherboards = $this->motherboardRepository->getAllMotherboards();
            return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard added failed']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem){
        $errors = [];

        if (empty($manufacture)) {
            $errors[] = 'Manufacturer is required.<br>';
        }

        if (empty($model)) {
            $errors[] = 'Model is required.<br>';
        }

        if (!is_numeric($price) || $price <= 0) {
            $errors[] = 'Price must be a positive number.<br>';
        }

        if (empty($chipset)) {
            $errors[] = 'Chipset is required.<br>';
        }
        
        if (!is_string($formFactor)) {
            $errors[] = 'Form factor must be a string.<br>';
        }

        if (!is_string($supportedMemory)) {
            $errors[] = 'Supported memory must be a string.<br>';
        }

        if (empty($socket)) {
            $errors[] = 'Socket is required.<br>';
        }

        if (empty($cpuArchitecture)) {
            $errors[] = 'Cpu architecture is required.<br>';
        }

        if (empty($internalConnectors)) {
            $errors[] = 'Internal Connectors are required.<br>';
        }

        if (empty($externalConnectors)) {
            $errors[] = 'External connectors is required.<br>';
        }

        if (!is_string($audioSystem)) {
            $errors[] = 'Audio system must be a string.<br>';
        }

        if (!ctype_digit($memorySlots) || $memorySlots < 0) {
            $errors[] = 'Memory slots must be an integer.<br>';
        }

        return $errors;
    }

    public function motherboardsEdit() {
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /motherboards');
            exit();
        }
    
        $motherboards = $this->motherboardRepository->getAllmotherboards();
        $this->render('motherboardsEdit', ['motherboards' => $motherboards]);
    }

    public function deleteMotherboard(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if ($this->isGet()) {
            $motherboardId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($motherboardId === false || $motherboardId <= 0) {
                header('Location: /motherboardsEdit?messages=invalid_id');
                exit();
            }

            try {
                $success = $this->motherboardRepository->deleteMotherboard($motherboardId);

                if ($success) {
                    $motherboards = $this->motherboardRepository->getAllMotherboards();
                    return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Motherboard deleted successfully']]);
                } else {
                    $motherboards = $this->motherboardRepository->getAllMotherboards();
                    return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Failed to delete motherboard']]);
                }
            } catch (PDOException $e) {
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['Error deleting motherboard']]);
            }
        }
    }

    public function updateMotherboard(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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

            $errors = $this->validateFormData($manufacture, $model, $price, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem );

            if (!empty($errors)) {
                return $this->render('motherboardsEdit', ['messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $motherboard = new Motherboard($id, $manufacture, $model, $price, $photo, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem);

            if(!$this->motherboardRepository->updateMotherboard($motherboard)){
                $motherboards = $this->motherboardRepository->getAllMotherboards();
                return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard updated successfully']]);
            }

            $motherboards = $this->motherboardRepository->getAllMotherboards();
            return $this->render('motherboardsEdit', ['motherboards' => $motherboards, 'messages' => ['motherboard updated failed']]);
        }
    }
    
}
