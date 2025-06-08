<div class="container py-5">
    <h1 class="mb-4">Mahesta Shop</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                        <p class="card-text"><?php echo $product['price']; ?> تومان</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="<?php echo Core\Auth::baseUrl('/admin'); ?>" class="btn btn-primary mt-4">Admin Panel</a>
</div>
