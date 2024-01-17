<?php

class RAM extends Product {
    private $speed;
    private $capacity;
    private $voltage;
    private $module_count;
    private $backlight;
    private $cooling;

    public function __construct($id, $manufacture, $model, $price, $photo, $speed, $capacity, $voltage, $module_count, $backlight, $cooling) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
        $this->speed = $speed;
        $this->capacity = $capacity;
        $this->voltage = $voltage;
        $this->module_count = $module_count;
        $this->backlight = $backlight;
        $this->cooling = $cooling;
    }

    public function getSpeed() {
        return $this->speed;
    }

    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    public function getVoltage() {
        return $this->voltage;
    }

    public function setVoltage($voltage) {
        $this->voltage = $voltage;
    }

    public function getModuleCount() {
        return $this->module_count;
    }

    public function setModuleCount($module_count) {
        $this->module_count = $module_count;
    }

    public function getBacklight() {
        return $this->backlight;
    }

    public function setBacklight($backlight) {
        $this->backlight = $backlight;
    }

    public function getCooling() {
        return $this->cooling;
    }

    public function setCooling($cooling) {
        $this->cooling = $cooling;
    }

}