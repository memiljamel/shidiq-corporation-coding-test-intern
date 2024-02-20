@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body table-responsive">

      @if (check_authorized("003U"))
        <a href="{{ route('app.products.create') }}" class="btn btn-success btn-sm mb-3">
          {{ __('Tambah') }}
        </a>
      @endif

      @if (check_authorized("003U"))
        <table class="table table-bordered" id="tableProducts">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th>Modal Produk</th>
              <th>Harga Jual</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      @endif

    </div>
  </div>

@endsection

@if (check_authorized("003U"))
  @push('script')
    <script>
      CORE.dataTableServer("tableProducts", "/app/products/get");
    </script>
  @endpush
@endif
