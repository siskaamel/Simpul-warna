<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel' }} - Simpul Warna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="#">Admin Simpul Warna</a>
        <div class="ms-auto">
            <a href="/" class="btn btn-outline-light btn-sm">Kembali ke Website</a>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html>
