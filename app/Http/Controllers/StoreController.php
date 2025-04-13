<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $categoryId = $request->input('category');
        $sort = $request->input('sort');

        $productsQuery = Product::query();

        // Filtro por categoria
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Ordenação
        if ($sort === 'name') {
            $productsQuery->orderBy('name');
        } elseif ($sort === 'price') {
            $productsQuery->orderBy('price');
        }

        $products = $productsQuery->get();
        $categories = Category::all();

        return view('store.index', compact('products', 'categories'));
    }
}
