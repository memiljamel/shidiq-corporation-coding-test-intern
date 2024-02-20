@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <a href="{{ route('app.products.edit', $product->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>

      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>Nama</th>
              <td>: {{ $product->product_name }}</td>
            </tr>
            <tr>
              <th>Deskripsi</th>
              <td>: {{ $product->product_description }}</td>
            </tr>
            <tr>
              <th>Modal Produk</th>
              <td>: {{ $product->product_price_capital }}</td>
            </tr>
            <tr>
              <th>Harga Jual</th>
              <td>: {{ $product->product_price_sell }}</td>
            </tr>
          </table>
        </div>
      </div>

    </div>
  </div>

@endsection
