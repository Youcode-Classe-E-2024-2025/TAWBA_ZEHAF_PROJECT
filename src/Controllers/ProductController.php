<?php

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $page = $_GET['page'] ?? 1;
        $category = $_GET['category'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $order = $_GET['order'] ?? 'asc';

        $products = $this->productModel->getProducts($page, $category, $sort, $order);
        $categories = $this->productModel->getCategories();

        require_once __DIR__ . '/../Views/products/index.php';
    }

    public function search() {
        $query = $_GET['query'] ?? '';
        $products = $this->productModel->searchProducts($query);
        require_once __DIR__ . '/../Views/products/search.php';
    }

    public function view($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: /products?error=Product not found');
            exit;
        }
        require_once __DIR__ . '/../Views/products/view.php';
    }

    public function adminIndex() {
        AuthMiddleware::requireAdmin();
        $products = $this->productModel->getAllProducts();
        require_once __DIR__ . '/../Views/admin/products/index.php';
    }

    public function create() {
        AuthMiddleware::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle product creation
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';

            if ($this->productModel->createProduct($name, $description, $price, $category)) {
                header('Location: /admin/products?message=Product created successfully');
                exit;
            } else {
                $error = "Failed to create product";
            }
        }
        require_once __DIR__ . '/../Views/admin/products/create.php';
    }

    public function edit($id) {
        AuthMiddleware::requireAdmin();
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: /admin/products?error=Product not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle product update
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category = $_POST['category'] ?? '';

            if ($this->productModel->updateProduct($id, $name, $description, $price, $category)) {
                header('Location: /admin/products?message=Product updated successfully');
                exit;
            } else {
                $error = "Failed to update product";
            }
        }
        require_once __DIR__ . '/../Views/admin/products/edit.php';
    }

    public function delete($id) {
        AuthMiddleware::requireAdmin();
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /admin/products?message=Product deleted successfully');
            exit;
        } else {
            header('Location: /admin/products?error=Failed to delete product');
            exit;
        }
    }
}