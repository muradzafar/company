<?php $config = require __DIR__ . '/../config.php'; ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($config['site_title']); ?></h1>
        <p class="lead">آخرین محصولات</p>
    </div>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x300?text=Product" class="card-img-top" alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2"><?php echo htmlspecialchars($product['title']); ?></h5>
                        <p class="text-muted mt-auto"><?php echo number_format($product['price']); ?> تومان</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <a href="<?php echo Core\Auth::baseUrl('/admin'); ?>" class="btn btn-outline-secondary">Admin Panel</a>
    </div>
</div>
