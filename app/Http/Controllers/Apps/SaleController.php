<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\SaleService  $sale_service
     * @return void
     */
    public function __construct(
        protected SaleService $sale_service
    ) {
    }

    /**
     * Get list of products
     *
     * @param Request $request
     */
    public function get(Request $request)
    {
        $sales = $this->sale_service->get_list_paged($request);
        $count = $this->sale_service->get_list_count($request);

        $data = [];
        $no = $request->start;

        foreach ($sales as $sale) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $sale->product_name;
            $row[] = $sale->quantity;
            $row[] = $sale->discount;
            $row[] = $sale->total_price;
            $button = "<a href='" . \route("app.sales.show", $sale->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
            if ($sale->role_id != 1 || $sale->id != \auth()->user()->id) {
                $button .= form_delete("formSales$sale->id", route("app.sales.destroy", $sale->id));
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
        return $this->view_admin("admin.sales.index", "Sale Management", [], TRUE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            "products" => Product::orderBy("product_name", "ASC")->get()
        ];
        return $this->view_admin("admin.sales.create", "Tambah Pesanan", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSaleRequest $request)
    {
        $response = $this->sale_service->store($request);
        return \response_json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        $data = [
            "sale" => $sale
        ];
        return $this->view_admin("admin.sales.show", "Detail Pesanan", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        $data = [
            "sale" => $sale,
            "products" => Product::orderBy("product_name", "ASC")->get()
        ];

        return $this->view_admin("admin.sales.edit", "Edit Pesanan", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $response = $this->sale_service->update($request, $sale);
        return \response_json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        $response = \response_success_default("Berhasil hapus pesanan!", FALSE, \route("app.sales.index"));
        return \response_json($response);
    }
}
