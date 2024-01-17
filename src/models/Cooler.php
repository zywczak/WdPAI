<?php

class Cooler extends Product {
    private $type;
    private $fan_count;
    private $fan_size;
    private $backlight;
    private $material;
    private $radiator_size;
    private $compatibility;

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

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getFanCount() {
        return $this->fan_count;
    }

    public function setFanCount($fan_count) {
        $this->fan_count = $fan_count;
    }

    public function getFanSize() {
        return $this->fan_size;
    }

    public function setFanSize($fan_size) {
        $this->fan_size = $fan_size;
    }

    public function getBacklight() {
        return $this->backlight;
    }

    public function setBacklight($backlight) {
        $this->backlight = $backlight;
    }

    public function getMaterial() {
        return $this->material;
    }

    public function setMaterial($material) {
        $this->material = $material;
    }

    public function getRadiatorSize() {
        return $this->radiator_size;
    }

    public function setRadiatorSize($radiator_size) {
        $this->radiator_size = $radiator_size;
    }

    public function getCompatibility() {
        return $this->compatibility;
    }

    public function setCompatibility($compatibility) {
        $this->compatibility = $compatibility;
    }
}