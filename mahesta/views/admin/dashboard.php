<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="display-6 mb-0"><?php echo $productCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="<?php echo Core\Auth::baseUrl('/admin/products'); ?>" class="card text-center text-decoration-none h-100">
                <div class="card-body">
                    <h5 class="card-title">Manage Products</h5>
                    <p class="card-text">View or add new products</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo Core\Auth::baseUrl('/admin/logout'); ?>" class="card text-center text-decoration-none h-100">
                <div class="card-body">
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Sign out of dashboard</p>
                </div>
            </a>
        </div>
    </div>
</div>
