<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/CPU.php';

class CpuRepository extends ProductRepository{
    public function addCpu(Cpu $cpu){
        $this->database->beginTransaction();

        try {
            parent::addProduct($cpu);

            $pdo = $this->database->connect();

            $stmt = $pdo->prepare('SELECT id FROM products ORDER BY id DESC LIMIT 1');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $productId = $result['id'];

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

                $this->database->commit();
            } else {
                $this->database->rollBack();
                throw new Exception("Nie udało się uzyskać ostatnio dodanego ID produktu.");
            }
            $pdo = null;
        } catch (\Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function getAllCpus(){
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
                $cpuData['photo'],
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

    public function deleteCpu(int $cpuId){
        $this->database->beginTransaction();

        try {
            $stmtCpu = $this->database->prepare('DELETE FROM cpu_details WHERE product_id = ?');
            $stmt->execute([
                $cpuId
            ]);

            $this->deleteProduct($cpuId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function updateCpu(Cpu $cpu){
        try {
            $productUpdated = $this->updateProduct($cpu);

            if (!$productUpdated) {
                return false;
            }

            $stmt = $this->database->connect()->prepare('
                UPDATE cpu_details
                SET
                    speed = ?,
                    architecture = ?,
                    supported_memory = ?,
                    cooling = ?,
                    threads = ?,
                    technological_process = ?,
                    power_consumption = ?
                WHERE product_id = ?
            ');

            $stmt->execute([
                $cpu->getId(),
                $cpu->getSpeed(),
                $cpu->getArchitecture(),
                $cpu->getSupportedMemory(),
                $cpu->getCooling(),
                $cpu->getThreads(),
                $cpu->getTechnologicalProcess(),
                $cpu->getPowerConsumption()
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating cpu: " . $e->getMessage());
            return false;
        }
    }
}
