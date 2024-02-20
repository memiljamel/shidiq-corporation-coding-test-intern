<?php namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\User;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use Illuminate\Http\Request;

class ProductService extends BaseService
{
  /**
   * Generate query index page
   *
   * @param Request $request
   */
  private function generate_query_get(Request $request)
  {
    $column_search = ["products.product_name", "products.product_description", "products.product_price_capital", "products.product_price_sell"];
    $column_order = [NULL, "products.product_name", "products.product_description", "products.product_price_capital", "products.product_price_sell"];
    $order = ["products.id" => "DESC"];

    $results = Product::query()
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
  public function store(StoreProductRequest $request)
  {
    try {
      $values = $request->validated();
      $product = Product::create([
          "product_name" => $values["product_name"],
          "product_description" => $values["product_description"],
          "product_price_capital" => $values["product_price_capital"],
          "product_price_sell" => $values["product_price_sell"],
      ]);

      $response = \response_success_default("Berhasil menambahkan produk!", $product->id, route("app.products.show", $product->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal store produk!");
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
  public function update(UpdateProductRequest $request, Product $product)
  {
    try {
      $product_id = $product->id;
      $values = $request->validated();

      // dd($values);
      $product->update([
          "product_name" => $values["product_name"],
          "product_description" => $values["product_description"],
          "product_price_capital" => $values["product_price_capital"],
          "product_price_sell" => $values["product_price_sell"],
      ]);

      $response = \response_success_default("Berhasil update data produk!", $product_id, route("app.products.show", $product->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal update produk!");
      $response = \response_errors_default();
    }

    return $response;
  }
}
