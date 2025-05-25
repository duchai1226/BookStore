<!-- Header Navigation -->

<head>
    <link rel="stylesheet" href="navigation.css">
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
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
                        <li><a class="dropdown-item" href="index.php">All Categories</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a class="dropdown-item <?php echo (isset($_GET['category']) && $_GET['category'] === $category['name']) ? 'active' : ''; ?>"
                                    href="index.php?category=<?php echo urlencode($category['name']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            <div class="d-flex">
                <a href="#" class="btn btn-outline-primary me-2">Sign In</a>
                <a href="#" class="btn btn-primary">Sign Up</a>
            </div>
        </div>
    </div>
</nav>