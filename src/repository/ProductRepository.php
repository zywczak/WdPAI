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
            $productData['photo']
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



    public function getAllProducts()
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

    public function clearCart(int $userId): bool
    {
        try {
            $stmt = $this->database->prepare('DELETE FROM basket WHERE user_id = :userId');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Obsługa błędów - możesz również zalogować błąd lub w inny sposób go obsłużyć
            error_log("Błąd czyszczenia koszyka: " . $e->getMessage());
            return false;
        }
    }

    public function remove(int $userId, int $productId){
        try {
            $stmt = $this->database->prepare('DELETE FROM basket WHERE user_id = :userId and product_id = :productId');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Obsługa błędów - możesz również zalogować błąd lub w inny sposób go obsłużyć
            error_log("Błąd usuwania produktu z koszyka: " . $e->getMessage());
            return false;
        }
    }

    public function addToCart(int $userId, int $productId)
{
    // Check if the product is already in the user's cart
    $stmt = $this->database->connect()->prepare('
        SELECT * FROM basket WHERE user_id = ? AND product_id = ?
    ');

    $stmt->execute([$userId, $productId]);
    $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCartItem) {
        // If the product is already in the cart, update the quantity
        $stmt = $this->database->connect()->prepare('
            UPDATE basket SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?
        ');

        $stmt->execute([$userId, $productId]);
    } else {
        // If the product is not in the cart, add it with quantity = 1
        $stmt = $this->database->connect()->prepare('
            INSERT INTO basket (user_id, product_id, quantity) VALUES (?, ?, 1)
        ');

        $stmt->execute([$userId, $productId]);
    }

    return true;
}


    public function updateProduct(Product $product)
{
    if ($product->getPhoto()){
        // Update product in the products table
        $stmt = $this->database->connect()->prepare('
            UPDATE products
            SET
                manufacturer = ?,
                model = ?,
                price = ?,
                photo = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $product->getManufacture(),
            $product->getModel(),
            $product->getPrice(),
            $product->getPhoto(),  // Include 'photo' property
            $product->getId()
        ]);
    }else{
        $stmt = $this->database->connect()->prepare('
            UPDATE products
            SET
                manufacturer = ?,
                model = ?,
                price = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $product->getManufacture(),
            $product->getModel(),
            $product->getPrice(),
            $product->getId()
        ]);
    }
}    
}
