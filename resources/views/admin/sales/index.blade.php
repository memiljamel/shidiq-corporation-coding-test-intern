@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body table-responsive">

      @if (check_authorized("003U"))
        <a href="{{ route('app.sales.create') }}" class="btn btn-success btn-sm mb-3">
          {{ __('Tambah') }}
        </a>
      @endif

      @if (check_authorized("003U"))
        <table class="table table-bordered" id="tableSales">
          <thead>
            <tr>
              <th>No</th>
              <th>Produk</th>
              <th>Qty</th>
              <th>Diskon</th>
              <th>Total Harga</th>
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
      CORE.dataTableServer("tableSales", "/app/sales/get");
    </script>
  @endpush
@endif
