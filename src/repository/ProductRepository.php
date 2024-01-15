<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Product.php';

class ProductRepository extends Repository
{
    public function getProduct(int $id): ?Product
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM products WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($productData === false) {
            return null;
        }

        return new Product(
            $productData['id'],
            $productData['manufacturer'],
            $productData['model'],
            $productData['price'],
            $productData['photo'],  // Now includes the 'photo' property
        );
    }

    public function addProduct(Product $product)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO products (manufacturer, model, price, photo, category_id)
            VALUES (?, ?, ?, ?, 3)
        ');

        $stmt->execute([
            $product->getManufacture(),
            $product->getModel(),
            $product->getPrice(),
            $product->getPhoto(),  // Include 'photo' property
        ]);
    }

    public function getAllProducts(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM products
        ');
        $stmt->execute();

        $products = [];

        while ($productData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product(
                $productData['id'],
                $productData['manufacturer'],
                $productData['model'],
                $productData['price'],
                $productData['photo'],  // Now includes the 'photo' property
                $productData['category_id']
            );
        }

        return $products;
    }

    public function addToCart(int $userId, int $productId, int $quantity)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO basket (user_id, product_id, quantity)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([$userId, $productId, $quantity]);
    }

    public function getUserCart(int $userId): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT p.*, b.quantity
            FROM basket b
            JOIN products p ON b.product_id = p.id
            WHERE b.user_id = ?
        ');

        $stmt->execute([$userId]);

        $cartItems = [];

        while ($itemData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cartItems[] = new Product(
                $itemData['id'],
                $itemData['manufacturer'],
                $itemData['model'],
                $itemData['price'],
                $itemData['photo'],
                $itemData['quantity']
            );
        }

        return $cartItems;
    }

    public function deleteProduct(int $productId): bool
    {
        try {
            $stmt = $this->database->prepare('DELETE FROM products WHERE id = :productId');
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Obsługa błędów - możesz również zalogować błąd lub w inny sposób go obsłużyć
            error_log("Błąd usuwania produktu: " . $e->getMessage());
            return false;
        }
    }

    public function updateProduct(Product $product)
{
    try {
        // Update product in the products table
        $stmt = $this->database->connect()->prepare('
            UPDATE products
            SET
                manufacturer = :manufacturer,
                model = :model,
                price = :price,
                photo = :photo
            WHERE id = :id
        ');

        $stmt->bindParam(':id', $product->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':manufacturer', $product->getManufacture(), PDO::PARAM_STR);
        $stmt->bindParam(':model', $product->getModel(), PDO::PARAM_STR);
        $stmt->bindParam(':price', $product->getPrice(), PDO::PARAM_INT);
        $stmt->bindParam(':photo', $product->getPhoto(), PDO::PARAM_STR);

        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            // Log the query and parameters for debugging
            error_log("Error updating product. Query: " . $stmt->queryString);
            error_log("Parameters: " . json_encode($stmt->debugDumpParams()));

            return false;
        }
    } catch (PDOException $e) {
        // Log or handle the exception
        error_log("Error updating product: " . $e->getMessage());
        return false;
    }
}


}
