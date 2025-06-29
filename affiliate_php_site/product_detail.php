<?php
// Individual product page
include 'includes/header.php';
require_once 'includes/product_functions.php'; // Use new product functions (DB version)

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($productId > 0) {
    $product = getProductByIdDB($productId); // Use the new DB function
}
?>

<section class="content-area product-detail-section">
    <?php if ($product): ?>
        <div class="product-detail-layout">
            <div class="product-detail-image-container">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'images/default_product.png'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-detail-image-placeholder">
            </div>
            <div class="product-detail-info">
                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p class="product-detail-price"><?php echo htmlspecialchars($product['currency']); ?> <?php echo htmlspecialchars($product['price']); ?></p>
                <p class="product-detail-category"><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); // This is category_name due to JOIN and alias ?></p>
                <p class="product-detail-type"><strong>Type:</strong> <?php echo htmlspecialchars($product['product_type']); ?></p>

                <h3>Description</h3>
                <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                <a href="<?php echo htmlspecialchars($product['affiliate_link']); ?>" target="_blank" class="btn-affiliate">
                    View Deal <span class="arrow">&rarr;</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <h2>Product Not Found</h2>
        <p>Sorry, the product you are looking for does not exist, the ID is invalid, or it could not be retrieved from the database.</p>
        <p><a href="products.php">Back to Products</a></p>
    <?php endif; ?>
</section>

<?php
include 'includes/footer.php';
?>
