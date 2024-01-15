<?php

require_once 'ProductRepository.php';
require_once __DIR__.'/../models/Cooler.php';

class CoolerRepository extends ProductRepository
{
    public function getCooler(int $productId): ?Cooler
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, c.*
            FROM products p
            JOIN cpu_cooling_details c ON p.id = c.product_id
            WHERE p.id = :id
        ');
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $coolerData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coolerData === false) {
            return null;
        }

        return new Cooler(
            $coolerData['id'],
            $coolerData['manufacturer'],
            $coolerData['model'],
            $coolerData['price'],
            $coolerData['photo'],  // Include 'photo' property
            $coolerData['category_id'],
            $coolerData['type'],
            $coolerData['fan_count'],
            $coolerData['fan_size'],
            $coolerData['backlight'],
            $coolerData['material'],
            $coolerData['radiator_size'],
            $coolerData['compatibility']
        );
    }

    public function addCooler(Cooler $cooler)
{
    $this->database->beginTransaction();

    try {
        // Wstawienie do tabeli 'products'
        parent::addProduct($cooler);

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
                INSERT INTO cpu_cooling_details (product_id, type, fan_count, fan_size, backlight, material, radiator_size, compatibility)
                VALUES (:productId, :type, :fanCount, :fanSize, :backlight, :material, :radiatorSize, :compatibility)
            ');

            $stmt->execute([
                ':productId' => $productId,
                ':type' => $cooler->getType(),
                ':fanCount' => $cooler->getFanCount(),
                ':fanSize' => $cooler->getFanSize(),
                ':backlight' => $cooler->getBacklight(),
                ':material' => $cooler->getMaterial(),
                ':radiatorSize' => $cooler->getRadiatorSize(),
                ':compatibility' => $cooler->getCompatibility()
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

    public function getAllCoolers(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, c.*
            FROM products p
            JOIN cpu_cooling_details c ON p.id = c.product_id
        ');
        $stmt->execute();

        $coolerList = [];

        while ($coolerData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coolerList[] = new Cooler(
                $coolerData['product_id'],
                $coolerData['manufacturer'],
                $coolerData['model'],
                $coolerData['price'],
                $coolerData['photo'],  // Include 'photo' property
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

    public function deleteCooler(int $coolerId): bool
    {
        $this->database->beginTransaction();

        try {
            // Usuń powiązane rekordy z cpu_cooling_details
            $stmtCpuCooling = $this->database->prepare('DELETE FROM cpu_cooling_details WHERE product_id = :coolerId');
            $stmtCpuCooling->bindParam(':coolerId', $coolerId, PDO::PARAM_INT);
            $stmtCpuCooling->execute();

            // Następnie usuń rekord z products
            $this->deleteProduct($coolerId);

            $this->database->commit();
            return true;
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    public function updateCooler(Cooler $cooler)
{
    try {
        // Call the updateProduct method from the parent class to update common product details
        $productUpdated = $this->updateProduct($cooler);

        // Check if the product was updated successfully before proceeding with cooler-specific update
        if (!$productUpdated) {
            // Handle the case where updating the product failed
            return false;
        }

        // Update cooler-specific fields in the cooler table
        $stmt = $this->database->connect()->prepare('
            UPDATE cpu_cooling_details
            SET
                type = :type,
                fan_count = :fanCount,
                fan_size = :fanSize,
                backlight = :backlight,
                material = :material,
                radiator_size = :radiatorSize,
                compatibility = :compatibility
            WHERE product_id = :id
        ');

        $stmt->bindParam(':id', $cooler->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':type', $cooler->getType(), PDO::PARAM_STR);
        $stmt->bindParam(':fanCount', $cooler->getFanCount(), PDO::PARAM_INT);
        $stmt->bindParam(':fanSize', $cooler->getFanSize(), PDO::PARAM_INT);
        $stmt->bindParam(':backlight', $cooler->getBacklight(), PDO::PARAM_BOOL);
        $stmt->bindParam(':material', $cooler->getMaterial(), PDO::PARAM_STR);
        $stmt->bindParam(':radiatorSize', $cooler->getRadiatorSize(), PDO::PARAM_STR);
        $stmt->bindParam(':compatibility', $cooler->getCompatibility(), PDO::PARAM_STR);

        $stmt->execute();

        // Check if the update was successful
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // Log or handle the exception
        error_log("Error updating cooler: " . $e->getMessage());
        return false;
    }
}

}