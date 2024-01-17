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

    public function __construct($id, $manufacture, $model, $price, $photo, $chipset, $form_factor, $supported_memory, $socket, $cpu_architecture, $internal_connectors, $external_connectors, $memory_slots, $audio_system) {
        parent::__construct($id, $manufacture, $model, $price, $photo);
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

    public function getChipset() {
        return $this->chipset;
    }

    public function setChipset($chipset) {
        $this->chipset = $chipset;
    }

    public function getFormFactor() {
        return $this->form_factor;
    }

    public function setFormFactor($form_factor) {
        $this->form_factor = $form_factor;
    }

    public function getSupportedMemory() {
        return $this->supported_memory;
    }

    public function setSupportedMemory($supported_memory) {
        $this->supported_memory = $supported_memory;
    }

    public function getSocket() {
        return $this->socket;
    }

    public function setSocket($socket) {
        $this->socket = $socket;
    }

    public function getCpuArchitecture() {
        return $this->cpu_architecture;
    }

    public function setCpuArchitecture($cpu_architecture) {
        $this->cpu_architecture = $cpu_architecture;
    }

    public function getInternalConnectors() {
        return $this->internal_connectors;
    }

    public function setInternalConnectors($internal_connectors) {
        $this->internal_connectors = $internal_connectors;
    }

    public function getExternalConnectors() {
        return $this->external_connectors;
    }

    public function setExternalConnectors($external_connectors) {
        $this->external_connectors = $external_connectors;
    }

    public function getMemorySlots() {
        return $this->memory_slots;
    }

    public function setMemorySlots($memory_slots) {
        $this->memory_slots = $memory_slots;
    }

    public function getAudioSystem() {
        return $this->audio_system;
    }

    public function setAudioSystem($audio_system) {
        $this->audio_system = $audio_system;
    }
}