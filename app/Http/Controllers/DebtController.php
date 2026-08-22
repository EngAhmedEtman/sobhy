<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        $customers = Customer::where('balance', '>', 0)->orderBy('id', 'desc')->paginate(20, ['*'], 'c_page');
        $suppliers = Supplier::where('balance', '>', 0)->orderBy('id', 'desc')->paginate(20, ['*'], 's_page');
        
        return view('debts.index', compact('customers', 'suppliers'));
    }
}
