<?php
// Categories listing page
include 'includes/header.php';
require_once 'includes/category_data.php'; // Include category data

$categories = getAllCategories(); // Get all categories
?>

<section class="content-area">
    <h2>Product Categories</h2>
    <p>Browse products by selecting a category below.</p>

    <?php if (!empty($categories)): ?>
        <ul class="category-list">
            <?php foreach ($categories as $category): ?>
                <li class="category-list-item">
                    <a href="products.php?category=<?php echo urlencode(htmlspecialchars($category)); ?>">
                        <?php echo htmlspecialchars($category); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No categories found.</p>
    <?php endif; ?>
</section>

<?php
include 'includes/footer.php';
?>
