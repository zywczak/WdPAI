<?php

class Cooler extends Product {
    private $type;
    private $fan_count;
    private $fan_size;
    private $backlight;
    private $material;
    private $radiator_size;
    private $compatibility;

    // Konstruktor klasy Cooler
    public function __construct($id, $manufacture, $model, $price, $photo, $type, $fan_count, $fan_size, $backlight, $material, $radiator_size, $compatibility) {
        parent::__construct($id, $manufacture, $model, $price, $photo);

        $this->type = $type;
        $this->fan_count = $fan_count;
        $this->fan_size = $fan_size;
        $this->backlight = $backlight;
        $this->material = $material;
        $this->radiator_size = $radiator_size;
        $this->compatibility = $compatibility;
    }

    // Getter dla type
    public function getType() {
        return $this->type;
    }

    // Setter dla type
    public function setType($type) {
        $this->type = $type;
    }

    // Getter dla fan_count
    public function getFanCount() {
        return $this->fan_count;
    }

    // Setter dla fan_count
    public function setFanCount($fan_count) {
        $this->fan_count = $fan_count;
    }

    // Getter dla fan_size
    public function getFanSize() {
        return $this->fan_size;
    }

    // Setter dla fan_size
    public function setFanSize($fan_size) {
        $this->fan_size = $fan_size;
    }

    // Getter dla backlight
    public function getBacklight() {
        return $this->backlight;
    }

    // Setter dla backlight
    public function setBacklight($backlight) {
        $this->backlight = $backlight;
    }

    // Getter dla material
    public function getMaterial() {
        return $this->material;
    }

    // Setter dla material
    public function setMaterial($material) {
        $this->material = $material;
    }

    // Getter dla radiator_size
    public function getRadiatorSize() {
        return $this->radiator_size;
    }

    // Setter dla radiator_size
    public function setRadiatorSize($radiator_size) {
        $this->radiator_size = $radiator_size;
    }

    // Getter dla compatibility
    public function getCompatibility() {
        return $this->compatibility;
    }

    // Setter dla compatibility
    public function setCompatibility($compatibility) {
        $this->compatibility = $compatibility;
    }
}