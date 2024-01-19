<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Product.php';

class ProductRepository extends Repository{
    public function getProduct(int $id){
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM products WHERE id = ?
        ');
        $stmt->execute([
            $id
        ]);

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

    public function addProduct(Product $product){
        
        $stmt = $this->database->connect()->prepare('
            INSERT INTO products (manufacturer, model, price, photo, category_id)
            VALUES (?, ?, ?, ?, 3)
        ');

        $stmt->execute([
            $product->getManufacture(),
            $product->getModel(),
            $product->getPrice(),
            $product->getPhoto(),
        ]);
    }

    public function getAllProducts(){
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
                $productData['photo'],
                $productData['category_id']
            );
        }

        return $products;
    }

    public function getUserCart(int $userId){
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM cart_view
            WHERE user_id = ?
        ');

        $stmt->execute([
            $userId
        ]);

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

    public function deleteProduct(int $productId){
        try {
            $stmt = $this->database->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([
                $productId
            ]);
    
            return true;
        } catch (PDOException $e) {
            error_log("Błąd usuwania produktu: " . $e->getMessage());
            return false;
        }
    }

    public function clearCart(int $userId){
        try {
            $stmt = $this->database->prepare('DELETE FROM basket WHERE user_id = ?');
            $stmt->execute([
                $userId
            ]);
    
            return true;
        } catch (PDOException $e) {
            error_log("Błąd czyszczenia koszyka: " . $e->getMessage());
            return false;
        }
    }

    public function remove(int $userId, int $productId){
        try {
            $stmt = $this->database->prepare('DELETE FROM basket WHERE user_id = ? and product_id = ?');
            $stmt->execute([
                $userId,
                $productId
            ]);
    
            return true;
        } catch (PDOException $e) {
            error_log("Błąd usuwania produktu z koszyka: " . $e->getMessage());
            return false;
        }
    }

    public function addToCart(int $userId, int $productId){
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM basket WHERE user_id = ? AND product_id = ?
        ');

        $stmt->execute([$userId, $productId]);
        $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCartItem) {
            $stmt = $this->database->connect()->prepare('
                UPDATE basket SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?
            ');

            $stmt->execute([$userId, $productId]);
        } else {
            $stmt = $this->database->connect()->prepare('
                INSERT INTO basket (user_id, product_id, quantity) VALUES (?, ?, 1)
            ');

            $stmt->execute([$userId, $productId]);
        }

        return true;
    }


    public function updateProduct(Product $product){
        if ($product->getPhoto()){
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
                $product->getPhoto(),
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
