<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/RAM.php';

class RamRepository extends ProductRepository
{
    public function getRam(int $productId): ?RAM
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, r.*
            FROM products p
            JOIN ram_details r ON p.id = r.product_id
            WHERE p.id = :id
        ');
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $ramData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ramData === false) {
            return null;
        }

        return new RAM(
            $ramData['id'],
            $ramData['manufacturer'],
            $ramData['model'],
            $ramData['price'],
            $ramData['photo'],  // Include 'photo' property
            $ramData['category_id'],
            $ramData['speed'],
            $ramData['capacity'],
            $ramData['voltage'],
            $ramData['module_count'],
            $ramData['backlight'],
            $ramData['cooling']
        );
    }

    public function addRam(RAM $ram)
    {
        parent::addProduct($ram);

        $productId = $this->database->lastInsertId();

        $stmt = $this->database->connect()->prepare('
            INSERT INTO ram_details (product_id, speed, capacity, voltage, module_count, backlight, cooling)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $productId,
            $ram->getSpeed(),
            $ram->getCapacity(),
            $ram->getVoltage(),
            $ram->getModuleCount(),
            $ram->getBacklight(),
            $ram->getCooling()
        ]);
    }

    public function getAllRams(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, r.*
            FROM products p
            JOIN ram_details r ON p.id = r.product_id
        ');
        $stmt->execute();

        $ramList = [];

        while ($ramData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ramList[] = new RAM(
                $ramData['id'],
                $ramData['manufacturer'],
                $ramData['model'],
                $ramData['price'],
                $ramData['photo'],  // Include 'photo' property
                $ramData['speed'],
                $ramData['capacity'],
                $ramData['voltage'],
                $ramData['module_count'],
                $ramData['backlight'],
                $ramData['cooling']
            );
        }

        return $ramList;
    }
}
