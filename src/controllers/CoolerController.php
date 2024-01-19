<?php

require_once 'ProductController.php';
require_once __DIR__ . '/../models/Cooler.php';
require_once __DIR__ . '/../repository/CoolerRepository.php';

class CoolerController extends ProductController{
    private $coolerRepository;

    public function __construct(){
        parent::__construct();
        $this->coolerRepository = new CoolerRepository();
    }

    public function coolers(){
        $coolers = $this->coolerRepository->getAllCoolers();
        $this->render('coolers', ['coolers' => $coolers]);
    }

    public function addCooler(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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

            $errors = $this->validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility);

            if (!empty($errors)) {
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $cooler = new Cooler($id, $manufacture, $model, $price, $photo, $type, $fanCount, $fanSize, $backlight, $material, $radiatorSize, $compatibility);

            if(!$this->coolerRepository->addCooler($cooler)){
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler added successfully']]);
            }

            $coolers = $this->coolerRepository->getAllCoolers();
            return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler added failed']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility){
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

        if (!is_string($type)) {
            $errors[] = 'Type must be a string.<br>';
        }

        if (!ctype_digit($fanCount) || $fanCount < 0) {
            $errors[] = 'Fan Count must be an integer.<br>';
        }

        if (!ctype_digit($fanSize) || $fanSize < 0) {
            $errors[] = 'Fan Size must be an integer.<br>';
        }

        if (empty($material)) {
            $errors[] = 'Material is required.<br>';
        }

        if (empty($radiatorSize)) {
            $errors[] = 'Radiator Size is required.<br>';
        }

        if (empty($compatibility)) {
            $errors[] = 'Compatibility is required.<br>';
        }

        return $errors;
    }

    public function coolersEdit(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /coolers');
            exit();
        }

        $coolers = $this->coolerRepository->getAllCoolers();
        $this->render('coolersEdit', ['coolers' => $coolers]);
    }

    public function deleteCooler(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if ($this->isGet()) {
            $coolerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($coolerId === false || $coolerId <= 0) {
                header('Location: /coolersEdit?messages=invalid_id');
                exit();
            }

            try {
                $success = $this->coolerRepository->deleteCooler($coolerId);

                if ($success) {
                    $coolers = $this->coolerRepository->getAllCoolers();
                    return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler deleted successfully']]);
                } else {
                    $coolers = $this->coolerRepository->getAllCoolers();
                    return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Failed to delete cooler']]);
                }
            } catch (PDOException $e) {
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Error deleting cooler']]);
            }
        }
    }

    public function updateCooler(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isPost()) {
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
    
            $errors = $this->validateFormData($manufacture, $model, $price, $type, $fanCount, $fanSize, $material, $radiatorSize, $compatibility);

            if (!empty($errors)) {
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();
            
            $cooler = new Cooler($id, $manufacture, $model, $price, $photo, $type, $fanCount, $fanSize, $backlight, $material, $radiatorSize, $compatibility);

            if(!$this->coolerRepository->updateCooler($cooler)){
                $coolers = $this->coolerRepository->getAllCoolers();
                return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler updated successfully']]);
            }

            $coolers = $this->coolerRepository->getAllCoolers();
            return $this->render('coolersEdit', ['coolers' => $coolers, 'messages' => ['Cooler updated failed']]);
        }
    }
}
