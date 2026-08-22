<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount(['transactions' => function ($query) {
            $query->where('type', 'رصيد افتتاحي');
        }])->orderBy('id', 'desc')->paginate(20);
        
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'stock' => $request->stock ?? 0,
            'unit' => 'كيلو',
            'notes' => $request->notes
        ]);

        if ($product->stock > 0) {
            $product->transactions()->create([
                'type' => 'رصيد افتتاحي',
                'quantity' => $product->stock,
                'balance_after' => $product->stock,
                'notes' => 'رصيد افتتاحي للمنتج'
            ]);
        }

        return back()->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'notes' => $request->notes
        ]);

        if ($request->filled('stock') && $request->stock > 0) {
            $hasOpening = $product->transactions()->where('type', 'رصيد افتتاحي')->exists();
            if (!$hasOpening) {
                $product->stock += $request->stock;
                $product->save();
                
                $product->transactions()->create([
                    'type' => 'رصيد افتتاحي',
                    'quantity' => $request->stock,
                    'balance_after' => $product->stock,
                    'notes' => 'رصيد افتتاحي للمنتج'
                ]);
            }
        }

        return back()->with('success', 'تم تعديل المنتج بنجاح');
    }

    public function priceInfo(Request $request, Product $product)
    {
        $type = $request->query('type'); // 'purchase' or 'sale'
        $entityId = $request->query('entity_id');

        $overallQuery = $type === 'purchase' ? \App\Models\PurchaseItem::class : \App\Models\SaleItem::class;
        $relation = $type === 'purchase' ? 'purchase' : 'sale';
        $entityColumn = $type === 'purchase' ? 'supplier_id' : 'customer_id';

        $lastOverall = $overallQuery::where('product_id', $product->id)
            ->latest('created_at')
            ->first();

        $lastForEntity = null;
        if ($entityId) {
            $lastForEntity = $overallQuery::where('product_id', $product->id)
                ->whereHas($relation, function($q) use ($entityColumn, $entityId) {
                    $q->where($entityColumn, $entityId);
                })
                ->latest('created_at')
                ->first();
        }

        return response()->json([
            'overall' => $lastOverall ? number_format($lastOverall->unit_price, 2) : 'لا يوجد',
            'entity' => $lastForEntity ? number_format($lastForEntity->unit_price, 2) : 'لا يوجد',
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $hasPurchases = \App\Models\PurchaseItem::where('product_id', $id)->exists();
        $hasSales = \App\Models\SaleItem::where('product_id', $id)->exists();
        $hasTransactions = $product->transactions()->exists();

        if ($hasPurchases || $hasSales || $hasTransactions) {
            return back()->with('error', 'لا يمكن حذف هذا المنتج لوجود حركات (مبيعات أو مشتريات) مرتبطة به.');
        }

        $product->delete();

        return back()->with('success', 'تم حذف المنتج بنجاح');
    }

    public function show($id)
    {
        $product = Product::with(['transactions' => function($q) {
            $q->orderBy('id', 'desc');
        }])->findOrFail($id);
        
        return view('products.show', compact('product'));
    }
}
