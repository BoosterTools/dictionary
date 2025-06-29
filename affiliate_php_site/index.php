<?php
// Homepage
include 'includes/header.php';
?>

<section class="hero-banner">
    <h2>Find Amazing Products!</h2>
    <p>Your one-stop shop for the best affiliate deals and recommendations.</p>
</section>

<section class="content-area">
    <h3>Featured Products</h3>

    <?php
    // Attempt to include product data.
    require_once 'includes/product_functions.php'; // Use new product functions (DB version)

    $allProducts = getAllProductsDB();
    // Select a few products to feature, e.g., the first 3
    // Later, this could be a specific query for "is_featured" products
    $featuredProducts = array_slice($allProducts, 0, 3);
    ?>

    <?php if (!empty($featuredProducts)): ?>
        <div class="product-grid featured-product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'images/default_product.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image-placeholder">
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="product-price"><?php echo htmlspecialchars($product['currency']); ?> <?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="btn-details">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No featured products available at the moment.</p>
    <?php endif; ?>

</section>

<?php
include 'includes/footer.php';
?>
