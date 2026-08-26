<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index(Request $request)
    {
        $tabs = [
            'customers_due_from' => [
                'label' => 'مطلوب من العملاء',
                'description' => 'مبالغ مستحقة لنا عند العملاء',
                'model' => Customer::class,
                'balance_operator' => '>',
                'party_label' => 'العميل',
                'amount_label' => 'المبلغ المطلوب من العميل',
                'action_label' => 'عرض وسداد',
                'route' => 'customers.show',
                'empty_message' => 'لا توجد مبالغ مطلوبة من العملاء حالياً.',
            ],
            'customers_due_to' => [
                'label' => 'مستحق للعملاء',
                'description' => 'أرصدة مستحقة للعملاء عندنا',
                'model' => Customer::class,
                'balance_operator' => '<',
                'party_label' => 'العميل',
                'amount_label' => 'المبلغ المستحق للعميل',
                'action_label' => 'عرض وتسوية',
                'route' => 'customers.show',
                'empty_message' => 'لا توجد مبالغ مستحقة للعملاء حالياً.',
            ],
            'suppliers_due_from' => [
                'label' => 'مطلوب من الموردين',
                'description' => 'مبالغ مستحقة لنا عند الموردين',
                'model' => Supplier::class,
                'balance_operator' => '<',
                'party_label' => 'المورد',
                'amount_label' => 'المبلغ المطلوب من المورد',
                'action_label' => 'عرض وتحصيل',
                'route' => 'suppliers.show',
                'empty_message' => 'لا توجد مبالغ مطلوبة من الموردين حالياً.',
            ],
            'suppliers_due_to' => [
                'label' => 'مستحق للموردين',
                'description' => 'مبالغ مستحقة للموردين علينا',
                'model' => Supplier::class,
                'balance_operator' => '>',
                'party_label' => 'المورد',
                'amount_label' => 'المبلغ المستحق للمورد',
                'action_label' => 'عرض وسداد',
                'route' => 'suppliers.show',
                'empty_message' => 'لا توجد مبالغ مستحقة للموردين حالياً.',
            ],
        ];

        $requestedTab = (string) $request->query('tab', 'customers_due_from');
        $tabAliases = [
            'customers' => 'customers_due_from',
            'suppliers' => 'suppliers_due_to',
        ];
        $tab = $tabAliases[$requestedTab] ?? $requestedTab;

        if (! array_key_exists($tab, $tabs)) {
            $tab = 'customers_due_from';
        }

        $activeTab = $tabs[$tab];
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $model = $activeTab['model'];

        $query = $model::query()
            ->where('balance', $activeTab['balance_operator'], 0);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        $debts = $query
            ->orderBy('balance', $activeTab['balance_operator'] === '>' ? 'desc' : 'asc')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax() || $request->has('ajax')) {
            return view('debts._content', compact('activeTab', 'search', 'debts'))->render();
        }

        $tabLabels = array_map(static fn (array $details): string => $details['label'], $tabs);

        return view('debts.index', compact('tabs', 'tabLabels', 'tab', 'activeTab', 'search', 'debts'));
    }
}
