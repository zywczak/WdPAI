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
            // Handle CPU addition logic based on the form submission
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category']; // Assuming you have a category field

            $speed = $_POST['speed'];
            $architecture = $_POST['architecture'];
            $supportedMemory = $_POST['supported_memory'];
            $cooling = isset($_POST['cooling']) ? true : false;
            $threads = $_POST['threads'];
            $technologicalProcess = $_POST['technological_process'];
            $powerConsumption = $_POST['power_consumption'];

            // Validate form data as needed
            // ...

            // Create a new CPU instance
            $cpu = new CPU($name, $price, $category, $speed, $architecture, $supportedMemory, $cooling, $threads, $technologicalProcess, $powerConsumption);

            // Add the CPU to the repository
            $this->cpuRepository->addCpu($cpu);

            // Optionally, redirect to the CPUs page or show a success message
            // ...

            return $this->render('add-cpu', ['messages' => ['CPU added successfully']]);
        }

        return $this->render('add-cpu', ['messages' => $this->message]);
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

    // Other CPU-related methods
    // ...
}
