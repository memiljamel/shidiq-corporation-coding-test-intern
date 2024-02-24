<div class="row align-items-center my-2">
    <div class="col-md-2">
        <label>Produk</label>
    </div>
    <div class="col-md-6">
        <select name="product_id" class="form-control">
            @foreach ($products as $product)
                <option {{ isset($sale->product_id) && $sale->product_id == $product->id ? "selected" : "" }} value="{{ $product->id }}">{{ $product->product_name }}</option>
            @endforeach
        </select>
        <div></div>
    </div>
</div>

<x-forms.input-grid col1="2" col2="6" label="Qty" name="quantity" type="number" value="{{ $sale->quantity ?? '' }}" placeholder="Masukan qty..."></x-forms.input-grid>

<x-forms.input-grid col1="2" col2="6" label="Diskon (%)" name="discount" type="number" value="{{ $sale->discount ?? '' }}" placeholder="Masukan diskon..."></x-forms.input-grid>

@push('script')
    <script src="{{ asset('assets/js/apps/user.js?v=' . random_string(6)) }}"></script>
@endpush
