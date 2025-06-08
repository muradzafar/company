<div class="hero mb-5">
    <div class="container">
        <h1 class="display-4 mb-3">Mahesta Shop</h1>
        <p class="lead">Explore our latest products</p>
        <a href="<?php echo Core\Auth::baseUrl('/admin'); ?>" class="btn btn-light">Admin Panel</a>
    </div>
</div>
<div class="container pb-5">
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                        <p class="card-text mb-0"><?php echo $product['price']; ?> تومان</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
