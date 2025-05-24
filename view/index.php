<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/BookModel.php';
require_once __DIR__ . '/../controller/search.php';

$database = new Database();
$db = $database->connect();
$bookModel = new BookModel($db);

// Pagination settings
$perPage = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;

// Initialize SearchController
$searchController = new SearchController($bookModel);

// Check if search is being performed
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedCategory = null;
if (!empty($searchKeyword)) {
    $searchResult = $searchController->search($searchKeyword, $page, $perPage);
    $books = $searchResult['books'];
    $totalPages = $searchResult['totalPages'];
    $totalBooks = $searchResult['totalBooks'];
} else {
    // Get selected category from URL
    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
    $totalBooks = $bookModel->getTotalBooks($selectedCategory);
    $totalPages = ceil($totalBooks / $perPage);
    $books = $bookModel->getBooks($page, $perPage, $selectedCategory);
}

// Get all categories
$categories = $bookModel->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    include __DIR__ . '/navigation.php';
    ?>

    <!-- Hero Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h1>Welcome to BookStore</h1>
            <p class="lead">Discover your next favorite book</p>
        </div>
    </div>

    <!-- Advertisement Banner -->
    <div class="container my-4">
        <div id="advertisementCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php
                $adsDir = __DIR__ . '/images/ads/';
                $ads = glob($adsDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                foreach ($ads as $key => $ad): ?>
                    <button type="button" data-bs-target="#advertisementCarousel" data-bs-slide-to="<?php echo $key; ?>"
                        <?php echo $key === 0 ? 'class="active"' : ''; ?>
                        aria-current="<?php echo $key === 0 ? 'true' : 'false'; ?>">
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner">
                <?php foreach ($ads as $key => $ad):
                    $adName = basename($ad); ?>
                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                        <img src="images/ads/<?php echo htmlspecialchars($adName); ?>" class="d-block" alt="Advertisement">
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($ads) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#advertisementCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#advertisementCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Books Section -->
    <div class="container py-5">
        <h2 class="mb-4">
            <?php echo $selectedCategory ? htmlspecialchars($selectedCategory) . ' Books' : 'Latest Books'; ?>
        </h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($books as $book): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php
                        $imagePath = 'images/books/' . ($book['image'] ?: 'default-book.jpg');
                        $fullImagePath = file_exists(__DIR__ . '/' . $imagePath) ? $imagePath : 'images/default-book.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($fullImagePath); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($book['title']); ?>" style="height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text">By <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="card-text text-primary fw-bold">$<?php echo number_format($book['price'], 2); ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="book-details.php?id=<?php echo $book['id']; ?>"
                                    class="btn btn-outline-primary">View Details</a>
                                <button class="btn btn-success">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous page link -->
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1;
                        echo $selectedCategory ? '&category=' . urlencode($selectedCategory) : '';
                        echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '';
                        ?>">Previous</a>
                    </li>

                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i;
                            echo $selectedCategory ? '&category=' . urlencode($selectedCategory) : '';
                            echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '';
                            ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next page link -->
                    <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1;
                        echo $selectedCategory ? '&category=' . urlencode($selectedCategory) : '';
                        echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : '';
                        ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php
    include __DIR__ . '/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>