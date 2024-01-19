<?php

require_once 'ProductController.php';
require_once __DIR__ . '/../models/CPU.php';
require_once __DIR__ . '/../repository/CpuRepository.php';

class CpuController extends ProductController{
    private $cpuRepository;

    public function __construct(){
        parent::__construct();
        $this->cpuRepository = new CpuRepository();
    }

    public function cpus(){
        $cpus = $this->cpuRepository->getAllCpus();
        $this->render('cpus', ['cpus' => $cpus]);
    }

    public function addCpu(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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

            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption );

            if (!empty($errors)) {
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $cpu = new Cpu($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption);

            if(!$this->cpuRepository->addCpu($cpu)){
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu added successfully']]);
            }

            $cpus = $this->cpuRepository->getAllCpus();
            return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu added failed']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption ){
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

        if (!is_numeric($speed) || $speed <= 0) {
            $errors[] = 'Speed must be a positive number.<br>';
        }

        if (empty($architecture)) {
            $errors[] = 'Architekture is required.<br>';
        }

        if (empty($supportedMemory)) {
            $errors[] = 'Supported memory is required.<br>';
        }

        if (!ctype_digit($threads) || $threads < 0) {
            $errors[] = 'Threads must be an integer.<br>';
        }

        if (!ctype_digit($technologicalProcess) || $technologicalProcess < 0) {
            $errors[] = 'Technological process must be an integer.<br>';
        }

        if (!ctype_digit($powerConsumption) || $powerConsumption < 0) {
            $errors[] = 'Power consumption must be an integer.<br>';
        }

        return $errors;
    }

    public function cpusEdit(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /cpus');
            exit();
        }

        $cpus = $this->cpuRepository->getAllCpus();
        $this->render('cpusEdit', ['cpus' => $cpus]);
    }

    public function deleteCpu(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if ($this->isGet()) {
            $cpuId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($cpuId === false || $cpuId <= 0) {
                header('Location: /cpusEdit?messages=invalid_id');
                exit();
            }

            try {
                $success = $this->cpuRepository->deleteCpu($cpuId);

                if ($success) {
                    $cpus = $this->cpuRepository->getAllCpus();
                    return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu deleted successfully']]);
                } else {
                    $cpus = $this->cpuRepository->getAllCpus();
                    return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Failed to delete cpu']]);
                }
            } catch (PDOException $e) {
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Error deleting cpu']]);
            }
        }
    }

    public function updateCpu(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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

            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption );

            if (!empty($errors)) {
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $cpu = new Cpu($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption);

            if(!$this->cpuRepository->updateCpu($cpu)){
                $cpus = $this->cpuRepository->getAllCpus();
                return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu updated successfully']]);
            }

            $cpus = $this->cpuRepository->getAllCpus();
            return $this->render('cpusEdit', ['cpus' => $cpus, 'messages' => ['Cpu updated failed']]);
        }
    }
}