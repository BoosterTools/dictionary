<?php
// Product listing page
include 'includes/header.php';
require_once 'includes/product_functions.php'; // Use new product functions (DB version)
require_once 'includes/category_data.php';    // For getAllCategoryNames (DB version)

// Get the current category from URL, if any
$currentCategoryName = isset($_GET['category']) ? trim($_GET['category']) : null;
$validCategoryNames = getAllCategoryNames(); // Get all valid category names from DB

$productsToDisplay = [];
$pageTitle = "Our Products";
$pageSubtitle = "Browse through our collection of curated products.";

if ($currentCategoryName && in_array($currentCategoryName, $validCategoryNames)) {
    $pageTitle = "Products in " . htmlspecialchars($currentCategoryName);
    $pageSubtitle = "Showing products for the category: " . htmlspecialchars($currentCategoryName);
    $productsToDisplay = getProductsByCategoryNameDB($currentCategoryName);
} elseif ($currentCategoryName && !in_array($currentCategoryName, $validCategoryNames)) {
    // Category provided in URL is not a valid category
    $pageTitle = "Invalid Category";
    $pageSubtitle = "The category '" . htmlspecialchars($currentCategoryName) . "' does not exist.";
    // productsToDisplay remains empty, or you could show all products as a fallback:
    // $productsToDisplay = getAllProductsDB();
    // For now, let's show a clear message and no products for an invalid category.
} else {
    // No category specified, or category is empty
    $productsToDisplay = getAllProductsDB();
}
?>

<section class="content-area">
    <h2><?php echo $pageTitle; ?></h2>
    <p><?php echo $pageSubtitle; ?></p>

    <?php if ($currentCategoryName && !in_array($currentCategoryName, $validCategoryNames) && !empty($currentCategoryName)): ?>
        <p style="color: red; font-weight: bold;">No products found for the category '<?php echo htmlspecialchars($currentCategoryName); ?>'. Please select a valid category from the <a href="categories.php">categories page</a>.</p>
    <?php endif; ?>

    <div class="product-grid">
        <?php if (!empty($productsToDisplay)): ?>
            <?php foreach ($productsToDisplay as $product): ?>
                <div class="product-card">
                    <?php // Note: $product['image_url'] and other fields come directly from DB ?>
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'images/default_product.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image-placeholder">
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="product-price"><?php echo htmlspecialchars($product['currency']); ?> <?php echo htmlspecialchars($product['price']); ?></p>
                    <p class="product-category">Category: <?php echo htmlspecialchars($product['category']); // This is category_name due to JOIN and alias ?></p>
                    <p class="product-type">Type: <?php echo htmlspecialchars($product['product_type']); ?></p>
                    <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="btn-details">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</section>

<?php
include 'includes/footer.php';
?>
