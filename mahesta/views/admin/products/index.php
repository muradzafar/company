<div class="container py-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Products</h2>
        <a href="<?php echo Core\Auth::baseUrl('/admin/products/create'); ?>" class="btn btn-success">Add Product</a>
    </div>
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
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
    <?php endif; ?>
</div>
