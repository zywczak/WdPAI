<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../repository/ProductRepository.php';

class ProductController extends AppController{
    private $productRepository;
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../../public/img/';

    public function __construct(){
        parent::__construct();
        $this->productRepository = new ProductRepository();
    }

    public function product(){
        $products = $this->productRepository->getProducts();
        $this->render('product', ['product' => $products]);
    }

    public function clearCart(){
        if (!$_SESSION['user_ID']) {
            header('Location: /login');
            exit();
        }

        try {
            $success = $this->productRepository->clearCart($_SESSION['user_ID']);

            if ($success) {
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
                $totalValue = $this->calculateTotalValue($cartItems);
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Cart cleared successfully']]);
            } else {
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
                $totalValue = $this->calculateTotalValue($cartItems);
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Failed to clear cart']]);
            }
        } catch (PDOException $e) {
            $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
            $totalValue = $this->calculateTotalValue($cartItems);
            return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Error clearing cart']]);
        }
    }

    public function remove(){
        if (!$_SESSION['user_ID']) {
            header('Location: /login'); 
            exit();
        }

        if ($this->isGet()) {
            $productId = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

            if ($productId === false || $productId <= 0) {
                header('Location: /cart?messages=invalid_product_id');
                exit();
            }

            try {
                $success = $this->productRepository->remove($_SESSION['user_ID'], $productId);

                if ($success) {
                    $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
                    $totalValue = $this->calculateTotalValue($cartItems);
                    return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Product removed successfully']]);
                } else {
                    $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
                    $totalValue = $this->calculateTotalValue($cartItems);   
                    return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Failed to remove product']]);
                }
            } catch (PDOException $e) {
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
                $totalValue = $this->calculateTotalValue($cartItems);
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Error removing product']]);
            }
        }
    }


    public function handleFileUpload(){
        $file = $_FILES['photo'];

        if (!in_array($file['type'], self::SUPPORTED_TYPES)) {
            return null;
        }

        $uploadPath = __DIR__ . self::UPLOAD_DIRECTORY;
        $photo = $file['name'];

        move_uploaded_file($file['tmp_name'], $uploadPath . $photo);

        return $photo;
    }

    public function addToCart() {
        header('Content-Type: application/json');
    
        if (!$_SESSION['user_ID']) {
            header('Location: /login'); 
            exit();
        }
    
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if (!is_array($data) || !isset($data['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data format']);
            exit();
        }
    
        $productId = $data['product_id'];
    
        $success = $this->productRepository->addToCart($_SESSION['user_ID'], $productId);
    
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Product added to cart successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
        }
        exit();
    }  

    public function cart(){
        if (!isset($_SESSION['user_ID'])) {
            header('Location: /login');
            exit();
        }

        $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);
        $totalValue = $this->calculateTotalValue($cartItems);
        return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue]);
    }

    function calculateTotalValue(array $cartItems){
        $totalValue = 0;
        foreach ($cartItems as $item) {
            $totalValue += $item->getPrice() * $item->getQuantity();
        }
        return $totalValue;
    }
}
