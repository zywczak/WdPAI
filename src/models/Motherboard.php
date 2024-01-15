<?php

class Motherboard extends Product {
    private $chipset;
    private $form_factor;
    private $supported_memory;
    private $socket;
    private $cpu_architecture;
    private $internal_connectors;
    private $external_connectors;
    private $memory_slots;
    private $audio_system;

    // Konstruktor klasy Motherboard
    public function __construct($id, $manufacture, $model, $price, $photo, $chipset, $form_factor, $supported_memory, $socket, $cpu_architecture, $internal_connectors, $external_connectors, $memory_slots, $audio_system) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
        $this->category_id = 4;
        $this->chipset = $chipset;
        $this->form_factor = $form_factor;
        $this->supported_memory = $supported_memory;
        $this->socket = $socket;
        $this->cpu_architecture = $cpu_architecture;
        $this->internal_connectors = $internal_connectors;
        $this->external_connectors = $external_connectors;
        $this->memory_slots = $memory_slots;
        $this->audio_system = $audio_system;
    }

    // Getter dla chipset
    public function getChipset() {
        return $this->chipset;
    }

    // Setter dla chipset
    public function setChipset($chipset) {
        $this->chipset = $chipset;
    }

    // Getter dla form_factor
    public function getFormFactor() {
        return $this->form_factor;
    }

    // Setter dla form_factor
    public function setFormFactor($form_factor) {
        $this->form_factor = $form_factor;
    }

    // Getter dla supported_memory
    public function getSupportedMemory() {
        return $this->supported_memory;
    }

    // Setter dla supported_memory
    public function setSupportedMemory($supported_memory) {
        $this->supported_memory = $supported_memory;
    }

    // Getter dla socket
    public function getSocket() {
        return $this->socket;
    }

    // Setter dla socket
    public function setSocket($socket) {
        $this->socket = $socket;
    }

    // Getter dla cpu_architecture
    public function getCpuArchitecture() {
        return $this->cpu_architecture;
    }

    // Setter dla cpu_architecture
    public function setCpuArchitecture($cpu_architecture) {
        $this->cpu_architecture = $cpu_architecture;
    }

    // Getter dla internal_connectors
    public function getInternalConnectors() {
        return $this->internal_connectors;
    }

    // Setter dla internal_connectors
    public function setInternalConnectors($internal_connectors) {
        $this->internal_connectors = $internal_connectors;
    }

    // Getter dla external_connectors
    public function getExternalConnectors() {
        return $this->external_connectors;
    }

    // Setter dla external_connectors
    public function setExternalConnectors($external_connectors) {
        $this->external_connectors = $external_connectors;
    }

    // Getter dla memory_slots
    public function getMemorySlots() {
        return $this->memory_slots;
    }

    // Setter dla memory_slots
    public function setMemorySlots($memory_slots) {
        $this->memory_slots = $memory_slots;
    }

    // Getter dla audio_system
    public function getAudioSystem() {
        return $this->audio_system;
    }

    // Setter dla audio_system
    public function setAudioSystem($audio_system) {
        $this->audio_system = $audio_system;
    }
}