@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <a href="{{ route('app.sales.edit', $sale->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>

      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>Produk</th>
              <td>: {{ $sale->product?->product_name }}</td>
            </tr>
            <tr>
              <th>Qty</th>
              <td>: {{ $sale->quantity }}</td>
            </tr>
            <tr>
              <th>Diskon</th>
              <td>: {{ $sale->discount }}</td>
            </tr>
            <tr>
              <th>Total Harga</th>
              <td>: {{ $sale->total_price }}</td>
            </tr>
          </table>
        </div>
      </div>

    </div>
  </div>

@endsection
