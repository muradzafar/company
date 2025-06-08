<div class="container py-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Products</h2>
        <a href="<?php echo Core\Auth::baseUrl('/admin/products/create'); ?>" class="btn btn-success">Add Product</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($products)): ?>
                <p class="mb-0">No products found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td><?php echo $product['price']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
