<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/Motherboard.php';

class MotherboardRepository extends ProductRepository
{
    public function getMotherboard(int $productId): ?Motherboard
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, m.*
            FROM products p
            JOIN motherboard_details m ON p.id = m.product_id
            WHERE p.id = :id
        ');
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $motherboardData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($motherboardData === false) {
            return null;
        }

        return new Motherboard(
            $motherboardData['id'],
            $motherboardData['manufacturer'],
            $motherboardData['model'],
            $motherboardData['price'],
            $motherboardData['photo'],  // Include 'photo' property
            $motherboardData['category_id'],
            $motherboardData['chipset'],
            $motherboardData['form_factor'],
            $motherboardData['supported_memory'],
            $motherboardData['socket'],
            $motherboardData['cpu_architecture'],
            $motherboardData['internal_connectors'],
            $motherboardData['external_connectors'],
            $motherboardData['memory_slots'],
            $motherboardData['audio_system']
        );
    }

    public function addMotherboard(Motherboard $motherboard)
    {
        parent::addProduct($motherboard);

        $productId = $this->database->lastInsertId();

        $stmt = $this->database->connect()->prepare('
            INSERT INTO motherboard_details (product_id, chipset, form_factor, supported_memory, socket, cpu_architecture, internal_connectors, external_connectors, memory_slots, audio_system)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $productId,
            $motherboard->getChipset(),
            $motherboard->getFormFactor(),
            $motherboard->getSupportedMemory(),
            $motherboard->getSocket(),
            $motherboard->getCpuArchitecture(),
            $motherboard->getInternalConnectors(),
            $motherboard->getExternalConnectors(),
            $motherboard->getMemorySlots(),
            $motherboard->getAudioSystem()
        ]);
    }

    public function getAllMotherboards(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, m.*
            FROM products p
            JOIN motherboard_details m ON p.id = m.product_id
        ');
        $stmt->execute();

        $motherboardList = [];

        while ($motherboardData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $motherboardList[] = new Motherboard(
                $motherboardData['id'],
                $motherboardData['manufacturer'],
                $motherboardData['model'],
                $motherboardData['price'],
                $motherboardData['photo'],  // Include 'photo' property
                $motherboardData['chipset'],
                $motherboardData['form_factor'],
                $motherboardData['supported_memory'],
                $motherboardData['socket'],
                $motherboardData['cpu_architecture'],
                $motherboardData['internal_connectors'],
                $motherboardData['external_connectors'],
                $motherboardData['memory_slots'],
                $motherboardData['audio_system']
            );
        }

        return $motherboardList;
    }
}
