<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/Motherboard.php';

class MotherboardRepository extends ProductRepository{
    public function addMotherboard(Motherboard $motherboard){
        $this->database->beginTransaction();

        try {
            parent::addProduct($motherboard);

            $pdo = $this->database->connect();

            $stmt = $pdo->prepare('SELECT id FROM products ORDER BY id DESC LIMIT 1');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $productId = $result['id'];

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

                $this->database->commit();
            } else {
                $this->database->rollBack();
                throw new Exception("Nie udało się uzyskać ostatnio dodanego ID produktu.");
            }
            $pdo = null;
        } catch (Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function getAllMotherboards(){
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, m.*
            FROM products p
            JOIN motherboard_details m ON p.id = m.product_id
        ');
        $stmt->execute();

        $motherboardList = [];

        while ($motherboardData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $motherboardList[] = new Motherboard(
                $motherboardData['product_id'],
                $motherboardData['manufacturer'],
                $motherboardData['model'],
                $motherboardData['price'],
                $motherboardData['photo'],
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

    public function deleteMotherboard(int $motherboardId){
        $this->database->beginTransaction();

        try {
            $stmtmotherboard = $this->database->prepare('DELETE FROM motherboard_details WHERE product_id = ?');
            $stmtmotherboard->execute([
                $motherboardId
            ]);
    
            $this->deleteProduct($motherboardId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function updateMotherboard(Motherboard $motherboard){
        try {
            $productUpdated = $this->updateProduct($motherboard);

            if (!$productUpdated) {
                return false;
            }

            $stmt = $this->database->connect()->prepare('
                UPDATE cpu_details
                SET
                    chipset = ?,
                    form_factor = ?, 
                    supported_memory = ?,
                    socket = ?, 
                    cpu_architecture = ?, 
                    internal_connectors = ?, 
                    external_connectors = ?, 
                    memory_slots = ?, 
                    audio_system = ?
                WHERE product_id = ?
            ');

            $stmt->execute([
                $motherboard->getId(),
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


            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating cpu: " . $e->getMessage());
            return false;
        }
    }
}
