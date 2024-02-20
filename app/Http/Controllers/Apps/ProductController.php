<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\ProductService  $product_service
     * @return void
     */
    public function __construct(
        protected ProductService $product_service
    ) {
    }

    /**
     * Get list of products
     *
     * @param Request $request
     */
    public function get(Request $request)
    {
        $products = $this->product_service->get_list_paged($request);
        $count = $this->product_service->get_list_count($request);

        $data = [];
        $no = $request->start;

        foreach ($products as $product) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $product->product_name;
            $row[] = $product->product_description;
            $row[] = $product->product_price_capital;
            $row[] = $product->product_price_sell;
            $button = "<a href='" . \route("app.products.show", $product->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            if ($product->role_id != 1 || $product->id != \auth()->user()->id) {
                $button .= form_delete("formProduct$product->id", route("app.products.destroy", $product->id));
            }
            $row[] = $button;
            $data[] = $row;
        }

        $output = [
            "draw" => $request->draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        ];

        return \response()->json($output, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view_admin("admin.products.index", "Produk Management", [], TRUE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view_admin("admin.products.create", "Tambah Produk");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $response = $this->product_service->store($request);

        return \response_json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $data = [
            "product" => $product
        ];

        return $this->view_admin("admin.products.show", "Detail Produk", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $data = [
            "product" => $product,
        ];

        return $this->view_admin("admin.products.edit", "Edit Produk", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $response = $this->product_service->update($request, $product);

        return \response_json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $response = \response_success_default("Berhasil hapus produk!", FALSE, \route("app.products.index"));
        return \response_json($response);
    }
}
