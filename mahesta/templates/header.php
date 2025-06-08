<?php
if (!isset($title)) {
    $title = 'Mahesta';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css">
    <link rel="stylesheet" href="<?php echo Core\Auth::baseUrl('/assets/style.css'); ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo Core\Auth::baseUrl(); ?>">Mahesta</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">دسته بندی ها</a>
                    <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="#">دسته ۱</a></li>
                        <li><a class="dropdown-item" href="#">دسته ۲</a></li>
                        <li><a class="dropdown-item" href="#">دسته ۳</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex ms-auto me-3" role="search">
                <input class="form-control" type="search" placeholder="جستجو" aria-label="Search">
                <button class="btn btn-link px-2" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <a href="#" class="nav-link position-relative me-3">
                <i class="bi bi-cart fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
            </a>
            <div class="d-lg-flex align-items-center d-none">
                <a href="tel:09123456789" class="nav-link text-primary">09123456789</a>
                <a href="tel:02112345678" class="nav-link text-primary">02112345678</a>
                <a href="https://wa.me/989123456789" class="nav-link text-success"><i class="bi bi-whatsapp fs-5"></i></a>
            </div>
        </div>
    </div>
</nav>
