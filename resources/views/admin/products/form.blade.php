<x-forms.input-grid col1="2" col2="6" label="Nama" name="product_name" value="{{ old('product_name', $product->product_name) }}" placeholder="Masukan nama produk..."></x-forms.input-grid>

<x-forms.textarea-grid col1="2" col2="6" label="Deskripsi" name="product_description" placeholder="Masukan deskripsi produk" value="{{ old('product_description', $product->product_description) }}"></x-forms.textarea-grid>

<x-forms.input-grid col1="2" col2="6" label="Modal Produk" name="product_price_capital" type="number" value="{{ old('product_price_capital', $product->product_price_capital) }}" placeholder="Masukan modal produk..."></x-forms.input-grid>

<x-forms.input-grid col1="2" col2="6" label="Harga Jual" name="product_price_sell" type="number" value="{{ old('product_price_sell', $product->product_price_sell) }}" placeholder="Masukan harga jual..."></x-forms.input-grid>

@push('script')
    <script src="{{ asset('assets/js/apps/user.js?v=' . random_string(6)) }}"></script>
@endpush
