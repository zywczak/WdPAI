<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/RAM.php';

class RamRepository extends ProductRepository{
    
    public function addRam(Ram $ram){
        $this->database->beginTransaction();

        try {
            parent::addProduct($ram);

            $pdo = $this->database->connect();

            $stmt = $pdo->prepare('SELECT id FROM products ORDER BY id DESC LIMIT 1');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $productId = $result['id'];

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
            
                $this->database->commit();
            } else {
                $this->database->rollBack();
                throw new Exception("Nie udało się uzyskać ostatnio dodanego ID produktu.");
            }
        } catch (Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function getAllRams(){
        $stmt = $this->database->connect()->prepare('
            SELECT products.*, ram_details.*
            FROM products
            JOIN ram_details ON products.id = ram_details.product_id
        ');
        $stmt->execute();

        $ramList = [];

        while ($ramData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ramList[] = new RAM(
                $ramData['product_id'],
                $ramData['manufacturer'],
                $ramData['model'],
                $ramData['price'],
                $ramData['photo'],
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

    public function deleteRam($ramId){

        $this->database->beginTransaction();

        try {
            $stmt = $this->database->prepare('DELETE FROM ram_details WHERE product_id = ?');
            $stmt->execute([
                $ramId,
            ]);

            $this->deleteProduct($ramId);

            $this->database->commit();
            return true;
        } catch (Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
