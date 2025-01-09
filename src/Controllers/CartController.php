<?php

require_once __DIR__ . '/../Models/Cart.php';

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function view() {
        $cartItems = $this->cartModel->getCartItems();
        require_once __DIR__ . '/../Views/cart/view.php';
    }

    public function addItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if ($productId) {
                $this->cartModel->addToCart($productId, $quantity);
                header('Location: /cart?message=Product added to cart');
                exit;
            }
        }
        header('Location: /products?error=Invalid request');
        exit;
    }

    public function removeItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;

            if ($productId) {
                $this->cartModel->removeFromCart($productId);
                header('Location: /cart?message=Product removed from cart');
                exit;
            }
        }
        header('Location: /cart?error=Invalid request');
        exit;
    }
}