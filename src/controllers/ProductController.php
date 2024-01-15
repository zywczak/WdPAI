<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../repository/ProductRepository.php';

class ProductController extends AppController
{
    private $productRepository;

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

    public function addProduct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle product addition logic based on the form submission
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category']; // Assuming you have a category field

            // Validate form data as needed
            // ...

            // Create a new Product instance
            $product = new Product($name, $price, $category);

            // Add the product to the repository
            $this->productRepository->addProduct($product);

            // Optionally, redirect to the products page or show a success message
            // ...

            return $this->render('add-product', ['messages' => ['Product added successfully']]);
        }

        return $this->render('add-product', ['messages' => $this->message]);
    }

    public function addToCart()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_ID'])) {
            // Redirect to the login page if not logged in
            header('Location: /login'); // Adjust the path accordingly
            exit();
        }

        if ($this->isPost()) {
            // Handle adding a product to the cart based on the form submission
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            // Validate form data as needed
            // ...

            // Add the product to the user's cart
            $this->productRepository->addToCart($_SESSION['user_ID'], $productId, $quantity);

            // Optionally, redirect to the cart page or show a success message
            // ...

            return $this->render('cart', ['messages' => ['Product added to cart successfully']]);
        }

        return $this->render('add-to-cart', ['messages' => $this->message]);
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
