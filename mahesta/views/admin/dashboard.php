<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <p class="card-text display-6"><?php echo $productCount; ?></p>
                    <a href="<?php echo Core\Auth::baseUrl('/admin/products'); ?>" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>
