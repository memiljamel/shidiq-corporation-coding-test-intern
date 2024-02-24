@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <form action="{{ route('app.sales.store') }}" method="POST" with-submit-crud>
        @csrf

        @include('admin.sales.form')

        <button class="btn btn-success btn-sm mt-3">Tambah Pesanan</button>

      </form>

    </div>
  </div>

@endsection
