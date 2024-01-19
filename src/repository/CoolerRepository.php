<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/Cooler.php';

class CoolerRepository extends ProductRepository{
    public function addCooler(Cooler $cooler){
        $this->database->beginTransaction();

        try {
            parent::addProduct($cooler);

            $pdo = $this->database->connect();

            $stmt = $pdo->prepare('SELECT id FROM products ORDER BY id DESC LIMIT 1');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $productId = $result['id'];

                $stmt = $this->database->prepare('
                    INSERT INTO cpu_cooling_details (product_id, type, fan_count, fan_size, backlight, material, radiator_size, compatibility)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ');

                $stmt->execute([
                    $productId,
                    $cooler->getType(),
                    $cooler->getFanCount(),
                    $cooler->getFanSize(),
                    $cooler->getBacklight(),
                    $cooler->getMaterial(),
                    $cooler->getRadiatorSize(),
                    $cooler->getCompatibility()
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

    public function getAllCoolers(){
        $stmt = $this->database->connect()->prepare('
            SELECT *
            FROM coolers_view
        ');
        $stmt->execute();

        $coolerList = [];

        while ($coolerData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coolerList[] = new Cooler(
                $coolerData['product_id'],
                $coolerData['manufacturer'],
                $coolerData['model'],
                $coolerData['price'],
                $coolerData['photo'],
                $coolerData['type'],
                $coolerData['fan_count'],
                $coolerData['fan_size'],
                $coolerData['backlight'],
                $coolerData['material'],
                $coolerData['radiator_size'],
                $coolerData['compatibility']
            );
        }
        return $coolerList;
    }

    public function deleteCooler(int $coolerId){
        $this->database->beginTransaction();

        try {
            $stmtCpuCooling = $this->database->prepare('DELETE FROM cpu_cooling_details WHERE product_id = ?');
            $stmtCpuCooling->execute([
                $coolerId
            ]);

            $this->deleteProduct($coolerId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function updateCooler(Cooler $cooler){
        try {
            $productUpdated = $this->updateProduct($cooler);

            if (!$productUpdated) {
                return false;
            }

            $stmt = $this->database->connect()->prepare('
                UPDATE cpu_cooling_details
                SET
                    type = ?,
                    fan_count = ?,
                    fan_size = ?,
                    backlight = ?,
                    material = ?,
                    radiator_size = ?,
                    compatibility = ?
                WHERE product_id = ?
            ');

            $stmt->execute([
                $cooler->getId(),
                $cooler->getType(),
                $cooler->getFanCount(),
                $cooler->getFanSize(),
                $cooler->getBacklight(),
                $cooler->getMaterial(),
                $cooler->getRadiatorSize(),
                $cooler->getCompatibility()
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating cooler: " . $e->getMessage());
            return false;
        }
    }
}