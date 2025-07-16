@extends('admin.layouts.app')

@section('content')
    <h3 class="mb-4">Admin Dashboard</h3>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="card-text fs-4">{{ $productCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Total Kategori</h5>
                    <p class="card-text fs-4">{{ $categoryCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Produk Tersinkron</h5>
                    <p class="card-text fs-4">{{ $syncedProducts }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Kategori Tersinkron</h5>
                    <p class="card-text fs-4">{{ $syncedCategories }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
