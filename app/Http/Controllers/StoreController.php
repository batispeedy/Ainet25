<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

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
