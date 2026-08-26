<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withCount(['transactions' => function ($query) {
            $query->where('type', 'رصيد افتتاحي');
        }]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        if ($request->filled('min_stock')) {
            $query->where('stock', '>=', $request->min_stock);
        }

        if ($request->filled('max_stock')) {
            $query->where('stock', '<=', $request->max_stock);
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('id', 'asc');
        } elseif ($sortBy === 'stock_desc') {
            $query->orderBy('stock', 'desc');
        } elseif ($sortBy === 'stock_asc') {
            $query->orderBy('stock', 'asc');
        } elseif ($sortBy === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(20)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('ajax')) {
            return view('products._table', compact('products'))->render();
        }

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
            'stock' => 'nullable|numeric|min:0',
            'notes' => ['nullable', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
        ], [
            'name.required' => 'يرجى إدخال اسم المنتج',
            'name.regex' => 'اسم المنتج يجب ألا يتكون من أرقام فقط',
            'stock.numeric' => 'يجب أن يكون الرصيد الافتتاحي رقماً صحيحاً أو عشرياً',
            'stock.min' => 'لا يمكن أن يكون الرصيد الافتتاحي بالسالب (أقل من صفر)',
            'notes.regex' => 'الملاحظات يجب ألا تتكون من أرقام فقط',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'stock' => $request->stock ?? 0,
            'opening_stock' => $request->stock ?? 0,
            'unit' => 'كيلو',
            'notes' => $request->notes,
        ]);

        if ($product->stock > 0) {
            $product->transactions()->create([
                'type' => 'رصيد افتتاحي',
                'quantity' => $product->stock,
                'balance_after' => $product->stock,
                'notes' => 'رصيد افتتاحي للمنتج',
            ]);
        }

        return back()->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
            'stock' => 'nullable|numeric|min:0',
            'notes' => ['nullable', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
        ], [
            'name.required' => 'يرجى إدخال اسم المنتج',
            'name.regex' => 'اسم المنتج يجب ألا يتكون من أرقام فقط',
            'stock.numeric' => 'يجب أن يكون الرصيد الافتتاحي رقماً صحيحاً أو عشرياً',
            'stock.min' => 'لا يمكن أن يكون الرصيد الافتتاحي بالسالب (أقل من صفر)',
            'notes.regex' => 'الملاحظات يجب ألا تتكون من أرقام فقط',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'notes' => $request->notes,
        ]);

        if ($request->filled('stock') && $request->stock > 0) {
            $hasOpening = $product->transactions()->where('type', 'رصيد افتتاحي')->exists();
            if (! $hasOpening) {
                $product->stock += $request->stock;
                $product->opening_stock += $request->stock;
                $product->save();

                $product->transactions()->create([
                    'type' => 'رصيد افتتاحي',
                    'quantity' => $request->stock,
                    'balance_after' => $product->stock,
                    'notes' => 'رصيد افتتاحي للمنتج',
                ]);
            }
        }

        return back()->with('success', 'تم تعديل المنتج بنجاح');
    }

    public function priceInfo(Request $request, Product $product)
    {
        $type = $request->query('type'); // 'purchase' or 'sale'
        $entityId = $request->query('entity_id');

        $overallQuery = $type === 'purchase' ? PurchaseItem::class : SaleItem::class;
        $relation = $type === 'purchase' ? 'purchase' : 'sale';
        $entityColumn = $type === 'purchase' ? 'supplier_id' : 'customer_id';

        $lastOverall = $overallQuery::where('product_id', $product->id)
            ->latest('created_at')
            ->first();

        $lastForEntity = null;
        if ($entityId) {
            $lastForEntity = $overallQuery::where('product_id', $product->id)
                ->whereHas($relation, function ($q) use ($entityColumn, $entityId) {
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

        $hasPurchases = PurchaseItem::where('product_id', $id)->exists();
        $hasSales = SaleItem::where('product_id', $id)->exists();
        $hasTransactions = $product->transactions()->exists();

        if ($hasPurchases || $hasSales || $hasTransactions) {
            return back()->with('error', 'لا يمكن حذف هذا المنتج لوجود حركات (مبيعات أو مشتريات) مرتبطة به.');
        }

        $product->delete();

        return back()->with('success', 'تم حذف المنتج بنجاح');
    }

    public function show($id)
    {
        $product = Product::with(['transactions' => function ($q) {
            $q->orderBy('id', 'desc');
        }])->findOrFail($id);

        return view('products.show', compact('product'));
    }
}
