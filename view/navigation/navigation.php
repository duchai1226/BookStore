<?php
// Đảm bảo biến $categories luôn tồn tại
if (!isset($categories) || empty($categories)) {
    if (!class_exists('BookModel')) {
        require_once __DIR__ . '/../../model/BookModel.php';
    }
    if (!class_exists('Database')) {
        require_once __DIR__ . '/../../config/Database.php';
    }

    $database = new Database();
    $db = $database->getConnection();
    $bookModel = new BookModel($db);
    $categories = $bookModel->getAllCategories();
}
?>
<!-- Header Navigation -->
<link rel="stylesheet" href="navigation/navigation.css">
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">TheBook.PK</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Blog</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo !isset($_GET['category']) ? 'active' : ''; ?>"
                                style="color: white;" href="index.php">All Categories</a></li>

                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a class="dropdown-item <?php echo (isset($_GET['category']) && $_GET['category'] === $category['name']) ? 'active' : ''; ?>"
                                        style="color: white;"
                                        href="index.php?category=<?php echo urlencode($category['name']); ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
            <div class="d-flex">
                <a href="#" class="btn btn-outline-light me-2">Sign In</a>
                <a href="#" class="btn btn-primary">Sign Up</a>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>