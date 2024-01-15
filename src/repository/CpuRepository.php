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

    public function addCpu(CPU $cpu)
    {
        parent::addProduct($cpu);

        $productId = $this->database->lastInsertId();

        $stmt = $this->database->connect()->prepare('
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

        return $cpuList;
    }
}
