<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-settings']);
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:150',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'stock_lower_limit'=> 'required|integer|min:0',
            'stock_upper_limit'=> 'required|integer|min:0',
            'discount_min_qty' => 'nullable|integer|min:1',
            'discount'         => 'nullable|numeric|min:0',
            'photo'            => 'nullable|image|max:2048',
            'description'      => 'nullable|string',
        ]);

        $data = $request->only([
            'name','category_id','price','stock',
            'stock_lower_limit','stock_upper_limit',
            'discount_min_qty','discount','description'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->photo->store('public/products');
            $data['photo'] = basename($file);
        }

        Product::create($data);
        return redirect()->route('products.index')
                         ->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'             => 'required|string|max:150',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'stock_lower_limit'=> 'required|integer|min:0',
            'stock_upper_limit'=> 'required|integer|min:0',
            'discount_min_qty' => 'nullable|integer|min:1',
            'discount'         => 'nullable|numeric|min:0',
            'photo'            => 'nullable|image|max:2048',
            'description'      => 'nullable|string',
        ]);

        $data = $request->only([
            'name','category_id','price','stock',
            'stock_lower_limit','stock_upper_limit',
            'discount_min_qty','discount','description'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->photo->store('public/products');
            $data['photo'] = basename($file);
        }

        $product->update($data);
        return redirect()->route('products.index')
                         ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
                         ->with('success', 'Produto removido.');
    }
}
