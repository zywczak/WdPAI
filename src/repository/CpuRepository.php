<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/CPU.php';

class CpuRepository extends ProductRepository
{
    public function getCpu(int $productId): ?CPU
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, c.*
            FROM products p
            JOIN cpu_details c ON p.id = c.product_id
            WHERE p.id = :id
        ');
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $cpuData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cpuData === false) {
            return null;
        }

        return new CPU(
            $cpuData['id'],
            $cpuData['manufacturer'],
            $cpuData['model'],
            $cpuData['price'],
            $cpuData['photo'],  // Include 'photo' property
            $cpuData['speed'],
            $cpuData['architecture'],
            $cpuData['supported_memory'],
            $cpuData['cooling'],
            $cpuData['threads'],
            $cpuData['technological_process'],
            $cpuData['power_consumption']
        );
    }


    public function addCpu(Cpu $cpu)
{
    $this->database->beginTransaction();

    try {
        // Wstawienie do tabeli 'products'
        parent::addProduct($cpu);

        // Pobranie instancji PDO z klasy Database
        $pdo = $this->database->connect();

        // Pobranie ostatnio dodanego ID produktu
        $stmt = $pdo->prepare('SELECT id FROM products ORDER BY id DESC LIMIT 1');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $productId = $result['id'];

            // Insert into 'cpu_cooling_details' table
            $stmt = $this->database->prepare('
                INSERT INTO cpu_details (product_id, speed, architecture, supported_memory, cooling, threads, technological_process, power_consumption)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $productId,
                $cpu->getSpeed(),
                $cpu->getArchitecture(),
                $cpu->getSupportedMemory(),
                $cpu->getCooling(),
                $cpu->getThreads(),
                $cpu->getTechnologicalProcess(),
                $cpu->getPowerConsumption()
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

    public function getAllCpus(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, c.*
            FROM products p
            JOIN cpu_details c ON p.id = c.product_id
        ');
        $stmt->execute();

        $cpuList = [];

        while ($cpuData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cpuList[] = new CPU(
                $cpuData['product_id'],
                $cpuData['manufacturer'],
                $cpuData['model'],
                $cpuData['price'],
                $cpuData['photo'],  // Include 'photo' property
                $cpuData['speed'],
                $cpuData['architecture'],
                $cpuData['supported_memory'],
                $cpuData['cooling'],
                $cpuData['threads'],
                $cpuData['technological_process'],
                $cpuData['power_consumption']
            );
        }

        return $cpuList;
    }

    public function deleteCpu(int $cpuId): bool
    {
        $this->database->beginTransaction();

        try {
            // Usuń powiązane rekordy z cpu_details
            $stmtCpu = $this->database->prepare('DELETE FROM cpu_details WHERE product_id = :cpuId');
            $stmtCpu->bindParam(':cpuId', $cpuId, PDO::PARAM_INT);
            $stmtCpu->execute();

            // Następnie usuń rekord z products
            $this->deleteProduct($cpuId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
