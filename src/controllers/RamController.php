<?php

require_once 'ProductController.php';
require_once __DIR__ . '/../models/RAM.php';
require_once __DIR__ . '/../repository/RamRepository.php';

class RamController extends ProductController{
    private $ramRepository;

    public function __construct(){
        parent::__construct();
        $this->ramRepository = new RamRepository();
    }

    public function rams(){
        $rams = $this->ramRepository->getAllRams();
        $this->render('rams', ['rams' => $rams]);
    }

    public function addRam(){
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
            $capacity = $_POST['capacity'];
            $voltage = $_POST['voltage'];
            $moduleCount = $_POST['module_count'];
            $backlight = isset($_POST['backlight']) ? true : 0;
            $cooling = isset($_POST['cooling']) ? true : 0;
            
            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if (!empty($errors)) {
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams,  'messages' => $errors]);
            }

            $photo = $this->handleFileUpload();
            $ram = new Ram($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if(!$this->ramRepository->addRam($ram)){
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Ram added successfully']]);
            }

            $rams = $this->ramRepository->getAllRams();
            return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Ram added failed']]);
        }
    }

    private function validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling){
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

    public function ramsEdit(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /rams');
            exit();
        }

        $rams = $this->ramRepository->getAllRams();
            $this->render('ramsEdit', ['rams' => $rams]);
    }

    public function deleteRam(){
        if ($_SESSION['user_type'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        if ($this->isGet()) {
            $ramId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($ramId === false || $ramId <= 0) {
                header('Location: /ramsEdit?messages=invalid_id');
                exit();
            }

            try {
                $success = $this->ramRepository->deleteRam($ramId);

                if ($success) {
                    $rams = $this->ramRepository->getAllRams();
                    return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Ram deleted successfully']]);
                } else {
                    $rams = $this->ramRepository->getAllRams();
                    return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Failed to delete ram']]);
                }
            } catch (PDOException $e) {
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramsEdit', ['rams' => $rams, 'messages' => ['Error deleting ram']]);
            }
        }
    }

    public function updateRam(){
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
            $capacity = $_POST['capacity'];
            $voltage = $_POST['voltage'];
            $moduleCount = $_POST['module_count'];
            $backlight = isset($_POST['backlight']) ? true : 0;
            $cooling = isset($_POST['cooling']) ? true : 0;

            $errors = $this->validateFormData($manufacture, $model, $price, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if (!empty($errors)) {
                return $this->render('ramsEdit', ['messages' => $errors]);
            }

            $photo = $this->handleFileUpload();

            $ram = new Ram($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            if(!$this->ramRepository->updateRam($ram)){
                $rams = $this->ramRepository->getAllRams();
                return $this->render('ramssEdit', ['rams' => $rams, 'messages' => ['Ram updated successfully']]);
            }

            $rams = $this->ramRepository->getAllRams();
            return $this->render('ramssEdit', ['rams' => $rams, 'messages' => ['Ram updated failed']]);
        }
    }

}