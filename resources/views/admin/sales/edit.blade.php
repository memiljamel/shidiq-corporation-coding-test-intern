@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <form action="{{ route('app.sales.update', $sale->id) }}" method="POST" with-submit-crud>
        @csrf
        @method("PUT")

        @include('admin.sales.form')

        <button class="btn btn-success btn-sm mt-3">Update Pesanan</button>

      </form>

    </div>
  </div>

@endsection
