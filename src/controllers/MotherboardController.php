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
            // Handle Motherboard addition logic based on the form submission
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category']; // Assuming you have a category field

            $chipset = $_POST['chipset'];
            $formFactor = $_POST['form_factor'];
            $supportedMemory = $_POST['supported_memory'];
            $socket = $_POST['socket'];
            $cpuArchitecture = $_POST['cpu_architecture'];
            $internalConnectors = $_POST['internal_connectors'];
            $externalConnectors = $_POST['external_connectors'];
            $memorySlots = $_POST['memory_slots'];
            $audioSystem = $_POST['audio_system'];

            // Validate form data as needed
            // ...

            // Create a new Motherboard instance
            $motherboard = new Motherboard($name, $price, $category, $chipset, $formFactor, $supportedMemory, $socket, $cpuArchitecture, $internalConnectors, $externalConnectors, $memorySlots, $audioSystem);

            // Add the Motherboard to the repository
            $this->motherboardRepository->addMotherboard($motherboard);

            // Optionally, redirect to the Motherboards page or show a success message
            // ...

            return $this->render('add-motherboard', ['messages' => ['Motherboard added successfully']]);
        }

        return $this->render('add-motherboard', ['messages' => $this->message]);
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
}
