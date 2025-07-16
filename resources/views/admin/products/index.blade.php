@extends('admin.layouts.app')

@section('content')
    <h3>Daftar Produk</h3>

    @if(session('successMessage'))
        <div class="alert alert-success">{{ session('successMessage') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Sinkronisasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <form id="sync-product-{{ $product->id }}" action="{{ route('admin.products.sync', $product->id) }}" method="POST">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" {{ $product->hub_product_id ? 'checked' : '' }}
                                   onchange="document.getElementById('sync-product-{{ $product->id }}').submit()">
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div>{{ $products->links() }}</div>
@endsection
