<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::whereNull('user_id')->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::doesntHave('categories')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:20',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|min:0',
            'description' => 'required|min:10|max:1024',
            'details' => 'required|min:3',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', __('ui.product_added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $product = Product::find($id);

        $categories = Category::doesntHave('categories')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3|max:20',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|min:0',
            'description' => 'required|min:10|max:1024',
            'details' => 'required|min:3',
        ]);

        $product = Product::find($id);
        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', __('ui.product_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', __('ui.product_removed'));
    }

    public function excel()
    {
        return view('admin.products.excel');
    }

    public function importFromExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/excel|file',
        ]);

        $array = Excel::toArray(collect([]), $request->file('file'));

        $result = $array[0];

        if ($result[0][0] !== 'Name' || $result[0][1] !== 'Select a category' || $result[0][2] !== 'Price' || $result[0][3] !== 'Description' || $result[0][4] !== 'Product content') {
            return redirect()->route('admin.products.excel')->with('success', __('ui.bad_format'));
        }

        unset($result[0]);

        foreach ($result as $index => $item) {
            if ($item[0] == null) unset($result[$index]);
        }

        $result = array_values($result);

        $data = [
            'results' => $result,
        ];

        $validator = Validator::make($data, [
            'results' => 'required|array',
            'results.*.0' => 'required|min:3|max:20',
            'results.*.1' => 'required|exists:categories,id',
            'results.*.2' => 'required|min:0',
            'results.*.3' => 'required|min:1|max:1024',
            'results.*.4' => 'required|min:3',
        ]);

        if ($validator->fails()){
            return redirect()->route('admin.products.excel')->with('success', $validator->errors()->first());
        }

        foreach ($result as $product) {
            Product::create([
                'title' => $product[0],
                'category_id' => $product[1],
                'price' => $product[2],
                'description' => $product[3],
                'details' => $product[4],
            ]);
        }

        return redirect()->route('admin.products.excel')->with('success', __('ui.products_import_success'));
    }
}
