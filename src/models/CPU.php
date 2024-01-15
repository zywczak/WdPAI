<?php

class CPU extends Product {
    private $speed;
    private $architecture;
    private $supported_memory;
    private $cooling;
    private $threads;
    private $technological_process;
    private $power_consumption;

    // Konstruktor klasy CPU
    public function __construct($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supported_memory, $cooling, $threads, $technological_process, $power_consumption) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
        $this->category_id = 2;
        $this->speed = $speed;
        $this->architecture = $architecture;
        $this->supported_memory = $supported_memory;
        $this->cooling = $cooling;
        $this->threads = $threads;
        $this->technological_process = $technological_process;
        $this->power_consumption = $power_consumption;
    }

    // Getter dla speed
    public function getSpeed() {
        return $this->speed;
    }

    // Setter dla speed
    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    // Getter dla architecture
    public function getArchitecture() {
        return $this->architecture;
    }

    // Setter dla architecture
    public function setArchitecture($architecture) {
        $this->architecture = $architecture;
    }

    // Getter dla supported_memory
    public function getSupportedMemory() {
        return $this->supported_memory;
    }

    // Setter dla supported_memory
    public function setSupportedMemory($supported_memory) {
        $this->supported_memory = $supported_memory;
    }

    // Getter dla cooling
    public function getCooling() {
        return $this->cooling;
    }

    // Setter dla cooling
    public function setCooling($cooling) {
        $this->cooling = $cooling;
    }

    // Getter dla threads
    public function getThreads() {
        return $this->threads;
    }

    // Setter dla threads
    public function setThreads($threads) {
        $this->threads = $threads;
    }

    // Getter dla technological_process
    public function getTechnologicalProcess() {
        return $this->technological_process;
    }

    // Setter dla technological_process
    public function setTechnologicalProcess($technological_process) {
        $this->technological_process = $technological_process;
    }

    // Getter dla power_consumption
    public function getPowerConsumption() {
        return $this->power_consumption;
    }

    // Setter dla power_consumption
    public function setPowerConsumption($power_consumption) {
        $this->power_consumption = $power_consumption;
    }
}