<?php namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use Illuminate\Http\Request;

class SaleService extends BaseService
{
  /**
   * Generate query index page
   *
   * @param Request $request
   */
  private function generate_query_get(Request $request)
  {
    $column_search = ["p.product_name", "sales.quantity", "sales.discount", "sales.total_price"];
    $column_order = [NULL, "p.product_name", "sales.quantity", "sales.discount", "sales.total_price"];
    $order = ["sales.id" => "DESC"];

    $results = Sale::query()
      ->join("products AS p", "p.id", "sales.product_id")
      ->select("sales.id", "p.product_name", "sales.quantity", "sales.discount", "sales.total_price")
      ->where(function ($query) use ($request, $column_search) {
        $i = 1;
        if (isset($request->search)) {
          foreach ($column_search as $column) {
            if ($request->search["value"]) {
              if ($i == 1) {
                $query->where($column, "LIKE", "%{$request->search["value"]}%");
              } else {
                $query->orWhere($column, "LIKE", "%{$request->search["value"]}%");
              }
            }
            $i++;
          }
        }
      });

    if (isset($request->order) && !empty($request->order)) {
      $results = $results->orderBy($column_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
    } else {
      $results = $results->orderBy(key($order), $order[key($order)]);
    }

    if (auth()->user()->role_id != 1) {
        $results->where("role_id", "!=", 1);
    }

    return $results;
  }

  public function get_list_paged(Request $request)
  {
    $results = $this->generate_query_get($request);
    if ($request->length != -1) {
      $limit = $results->offset($request->start)->limit($request->length);
      return $limit->get();
    }
  }

  public function get_list_count(Request $request)
  {
    return $this->generate_query_get($request)->count();
  }

  /**
   * Store new user
   *
   * @param Request $request
   */
  public function store(StoreSaleRequest $request)
  {
    try {
      $values = $request->validated();

      $product = Product::findOrFail($values["product_id"]);

      $total_price = $product->product_price_sell * $values["quantity"];
      $discount_price = $values["discount"] > 0 ? $total_price - ($total_price * ($values["discount"] / 100)) : $total_price;

      $sales = Sale::create([
          "product_id" => $product->id,
          "quantity" => $values["quantity"],
          "discount" => $values["discount"],
          "total_price" => $discount_price,
      ]);

      $response = \response_success_default("Berhasil menambahkan pesanan!", $sales->id, route("app.sales.show", $sales->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal store pesanan!");
      $response = \response_errors_default();
    }

    return $response;
  }

  /**
   * Update new user
   *
   * @param Request $request
   * @param User $user
   */
  public function update(UpdateSaleRequest $request, Sale $sale)
  {
    try {
      $sale_id = $sale->id;
      $values = $request->validated();

      $total_price = $sale?->product->product_price_sell * $values["quantity"];
      $discount_price = $values["discount"] > 0 ? $total_price - ($total_price * ($values["discount"] / 100)) : $total_price;

      // dd($values);
      $sale->update([
          "quantity" => $values["quantity"],
          "discount" => $values["discount"],
          "total_price" => $discount_price,
      ]);

      $response = \response_success_default("Berhasil update data pesanan!", $sale_id, route("app.sales.show", $sale->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal update pesanan!");
      $response = \response_errors_default();
    }

    return $response;
  }
}
