<?php

class RAM extends Product {
    private $speed;
    private $capacity;
    private $voltage;
    private $module_count;
    private $backlight;
    private $cooling;

    // Konstruktor klasy RAM
    public function __construct($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $module_count, $backlight, $cooling) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
        $this->category_id = 1;
        $this->speed = $speed;
        $this->capacity = $capacity;
        $this->voltage = $voltage;
        $this->module_count = $module_count;
        $this->backlight = $backlight;
        $this->cooling = $cooling;
    }

    // Getter dla speed
    public function getSpeed() {
        return $this->speed;
    }

    // Setter dla speed
    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    // Getter dla capacity
    public function getCapacity() {
        return $this->capacity;
    }

    // Setter dla capacity
    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    // Getter dla voltage
    public function getVoltage() {
        return $this->voltage;
    }

    // Setter dla voltage
    public function setVoltage($voltage) {
        $this->voltage = $voltage;
    }

    // Getter dla module_count
    public function getModuleCount() {
        return $this->module_count;
    }

    // Setter dla module_count
    public function setModuleCount($module_count) {
        $this->module_count = $module_count;
    }

    // Getter dla backlight
    public function getBacklight() {
        return $this->backlight;
    }

    // Setter dla backlight
    public function setBacklight($backlight) {
        $this->backlight = $backlight;
    }

    // Getter dla cooling
    public function getCooling() {
        return $this->cooling;
    }

    // Setter dla cooling
    public function setCooling($cooling) {
        $this->cooling = $cooling;
    }

}