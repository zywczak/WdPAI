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
    $this->database->beginTransaction();

    try {
        // Wstawienie do tabeli 'products'
        parent::addProduct($motherboard);

        // Pobranie instancji PDO z klasy Database
        $pdo = $this->database->connect();

        // Pobranie ostatnio dodanego ID produktu
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

            // Commit the transaction
            $this->database->commit();
        } else {
            // Coś poszło nie tak, wycofaj transakcję
            $this->database->rollBack();
            throw new Exception("Nie udało się uzyskać ostatnio dodanego ID produktu.");
        }
        $pdo = null;
    } catch (\Exception $e) {
        // Wycofanie transakcji w przypadku wystąpienia wyjątku
        $this->database->rollBack();
        // Obsługa wyjątku (np. logowanie lub rzucenie go dalej)
        throw $e;
    }
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
                $motherboardData['product_id'],
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

    public function deleteMotherboard(int $motherboardId): bool
    {
        $this->database->beginTransaction();

        try {
            // Usuń powiązane rekordy z motherboard_details
            $stmtmotherboard = $this->database->prepare('DELETE FROM motherboard_details WHERE product_id = :motherboardId');
            $stmtmotherboard->bindParam(':motherboardId', $motherboardId, PDO::PARAM_INT);
            $stmtmotherboard->execute();

            // Następnie usuń rekord z products
            $this->deleteProduct($motherboardId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
