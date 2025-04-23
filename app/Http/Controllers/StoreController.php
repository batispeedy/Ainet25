<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class StoreController extends Controller
{
    /**
     * Exibe o catálogo público de produtos com filtros e ordenação.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Ordenação simples: 'name' ou 'price'
        switch ($request->sort) {
            case 'price':
                $query->orderBy('price', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('store.index', compact('products', 'categories'));
    }
}
