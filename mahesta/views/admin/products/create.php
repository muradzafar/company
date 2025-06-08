<div class="container py-5" style="max-width:500px;">
    <h2 class="mb-4">Add Product</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Save</button>
        <a href="<?php echo Core\Auth::baseUrl('/admin/products'); ?>" class="d-block text-center mt-3">Back to list</a>
    </form>
</div>
