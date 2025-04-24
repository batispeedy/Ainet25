<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingShippingCost;

class ShippingCostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','can:manage-settings']);
    }


    public function index()
    {
        $costs = SettingShippingCost::orderBy('min_value_threshold')->paginate(15);
        return view('shipping_costs.index', compact('costs'));
    }


    public function create()
    {
        return view('shipping_costs.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'min_value_threshold' => 'required|numeric|min:0',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
            'shipping_cost'       => 'required|numeric|min:0',
        ]);

        SettingShippingCost::create($request->only([
            'min_value_threshold','max_value_threshold','shipping_cost'
        ]));

        return redirect()->route('shipping_costs.index')
                         ->with('success','Faixa de portes criada.');
    }


    public function edit(SettingShippingCost $shipping_cost)
    {
        return view('shipping_costs.edit', compact('shipping_cost'));
    }


    public function update(Request $request, SettingShippingCost $shipping_cost)
    {
        $request->validate([
            'min_value_threshold' => 'required|numeric|min:0',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
            'shipping_cost'       => 'required|numeric|min:0',
        ]);

        $shipping_cost->update($request->only([
            'min_value_threshold','max_value_threshold','shipping_cost'
        ]));

        return redirect()->route('shipping_costs.index')
                         ->with('success','Faixa de portes atualizada.');
    }

    public function destroy(SettingShippingCost $shipping_cost)
    {
        $shipping_cost->delete();
        return redirect()->route('shipping_costs.index')
                         ->with('success','Faixa de portes removida.');
    }
}
