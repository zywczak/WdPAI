<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../repository/ProductRepository.php';

class ProductController extends AppController
{
    private $productRepository;
    const MAX_FILE_SIZE = 1024 * 1024; // 1 MB
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../../public/img/';

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new ProductRepository();
    }

    public function product()
    {
        $products = $this->productRepository->getProducts();
        $this->render('product', ['product' => $products]);
    }

    public function clearCart()
{
    // Check if user is logged in and is an admin
    if (!$_SESSION['user_ID']) {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

        try {
            // Call the repository method to delete the cooler
            $success = $this->productRepository->clearCart($_SESSION['user_ID']);

            if ($success) {
                // Product deleted successfully, redirect to the coolersEdit page with a success message
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Cart cleared successfully']]);
            } else {
                // Product deletion failed, redirect to the coolersEdit page with an error message
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Failed to clear cart']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the coolersEdit page with an error message
            $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
            return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Error clearing cart']]);
        }
    }


    public function remove()
{
    // Check if user is logged in and is an admin
    if (!$_SESSION['user_ID']) {
        // Redirect to the unauthorized page if not an admin
        header('Location: /login'); // Adjust the path accordingly
        exit();
    }

    if ($this->isGet()) {
        // Get the cooler ID from the query parameters
        $productId = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

        // Validate the ID
        if ($productId === false || $productId <= 0) {
            // Invalid ID, handle accordingly (show error message, redirect, etc.)
            // For now, redirect to the coolersEdit page with an error message
            header('Location: /cart?messages=invalid_product_id');
            exit();
        }

        try {
            // Call the repository method to delete the cooler
            $success = $this->productRepository->remove($_SESSION['user_ID'], $productId);

            if ($success) {
                // Product deleted successfully, redirect to the coolersEdit page with a success message
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Product removed successfully']]);
            } else {
                // Product deletion failed, redirect to the coolersEdit page with an error message
                $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
                return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Failed to remove product']]);
            }
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            // For now, redirect to the coolersEdit page with an error message
            $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

                // Calculate the total value of items in the cart
                $totalValue = $this->calculateTotalValue($cartItems);
        
                // Optionally, you can do something with the total value
        
            return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue, 'messages' => ['Error removing product']]);
        }
    }
}


    public function handleFileUpload()
    {
        $file = $_FILES['photo'];

        // Check if the file is an image (JPEG or PNG)
        if (!in_array($file['type'], self::SUPPORTED_TYPES)) {
            // Invalid file type, handle accordingly (show error message, redirect, etc.)
            return null;
        }

        $uploadPath = __DIR__ . self::UPLOAD_DIRECTORY;
        $photo = $file['name'];

        // Save uploaded file to a folder
        move_uploaded_file($file['tmp_name'], $uploadPath . $photo);

        return $photo;
    }

    public function addToCart()
{
    // Check if user is logged in
    if (!isset($_SESSION['user_ID'])) {
        // Return some JSON indicating that the user is not logged in
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    // Check if it's a POST request
    if ($this->isPost()) {
        // Handle adding a product to the cart based on the form submission
        $productId = $_POST['product_id'];

        // Add the product to the user's cart or update quantity
        $success = $this->productRepository->addToCart($_SESSION['user_ID'], $productId);

        // Return JSON response indicating success or failure
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Product added to cart successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
        }
        exit();
    }

    // Return some JSON indicating an invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}


    public function cart()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        // Retrieve user's cart items from the repository
        $cartItems = $this->productRepository->getUserCart($_SESSION['user_ID']);

        // Calculate the total value of items in the cart
        $totalValue = $this->calculateTotalValue($cartItems);

        // Optionally, you can do something with the total value

        return $this->render('cart', ['cartItems' => $cartItems, 'totalValue' => $totalValue]);
    }

    function calculateTotalValue(array $cartItems): float
    {
    $totalValue = 0;
    foreach ($cartItems as $item) {
        $totalValue += $item->getPrice() * $item->getQuantity();
    }
    return $totalValue;
    }
}
