<?php

class CPU extends Product {
    private $speed;
    private $architecture;
    private $supported_memory;
    private $cooling;
    private $threads;
    private $technological_process;
    private $power_consumption;

    public function __construct($id, $manufacture, $model, $price, $photo, $speed, $architecture, $supported_memory, $cooling, $threads, $technological_process, $power_consumption) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
        $this->speed = $speed;
        $this->architecture = $architecture;
        $this->supported_memory = $supported_memory;
        $this->cooling = $cooling;
        $this->threads = $threads;
        $this->technological_process = $technological_process;
        $this->power_consumption = $power_consumption;
    }

    public function getSpeed() {
        return $this->speed;
    }

    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    public function getArchitecture() {
        return $this->architecture;
    }

    public function setArchitecture($architecture) {
        $this->architecture = $architecture;
    }

    public function getSupportedMemory() {
        return $this->supported_memory;
    }

    public function setSupportedMemory($supported_memory) {
        $this->supported_memory = $supported_memory;
    }

    public function getCooling() {
        return $this->cooling;
    }

    public function setCooling($cooling) {
        $this->cooling = $cooling;
    }

    public function getThreads() {
        return $this->threads;
    }

    public function setThreads($threads) {
        $this->threads = $threads;
    }

    public function getTechnologicalProcess() {
        return $this->technological_process;
    }

    public function setTechnologicalProcess($technological_process) {
        $this->technological_process = $technological_process;
    }

    public function getPowerConsumption() {
        return $this->power_consumption;
    }

    public function setPowerConsumption($power_consumption) {
        $this->power_consumption = $power_consumption;
    }
}