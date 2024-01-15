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
            // Handle RAM addition logic based on the form submission
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category']; // Assuming you have a category field

            $speed = $_POST['speed'];
            $capacity = $_POST['capacity'];
            $voltage = $_POST['voltage'];
            $moduleCount = $_POST['module_count'];
            $backlight = isset($_POST['backlight']) ? true : false;
            $cooling = isset($_POST['cooling']) ? true : false;

            // Validate form data as needed
            // ...

            // Create a new RAM instance
            $ram = new RAM($name, $price, $category, $speed, $capacity, $voltage, $moduleCount, $backlight, $cooling);

            // Add the RAM to the repository
            $this->ramRepository->addRam($ram);

            // Optionally, redirect to the RAMs page or show a success message
            // ...

            return $this->render('add-ram', ['messages' => ['RAM added successfully']]);
        }

        return $this->render('add-ram', ['messages' => $this->message]);
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
    // Other RAM-related methods
    // ...
}
