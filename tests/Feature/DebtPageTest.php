<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebtPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_debt_tab_shows_the_correct_balance_direction(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        Customer::create(['name' => 'عميل مدين', 'phone' => '01010000001', 'balance' => 1200]);
        Customer::create(['name' => 'عميل دائن', 'phone' => '01010000002', 'balance' => -300]);
        Supplier::create(['name' => 'مورد مدين', 'phone' => '01110000001', 'balance' => -450]);
        Supplier::create(['name' => 'مورد دائن', 'phone' => '01110000002', 'balance' => 2100]);

        $this->get(route('debts.index'))
            ->assertOk()
            ->assertSee('عميل مدين')
            ->assertDontSee('عميل دائن')
            ->assertSee('<table', false);

        $this->get(route('debts.index', ['tab' => 'customers_due_to']))
            ->assertOk()
            ->assertSee('عميل دائن')
            ->assertDontSee('عميل مدين');

        $this->get(route('debts.index', ['tab' => 'suppliers_due_from']))
            ->assertOk()
            ->assertSee('مورد مدين')
            ->assertDontSee('مورد دائن');

        $this->get(route('debts.index', ['tab' => 'suppliers_due_to']))
            ->assertOk()
            ->assertSee('مورد دائن')
            ->assertDontSee('مورد مدين');
    }

    public function test_search_filters_only_the_active_tab_by_name_or_phone(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        Supplier::create(['name' => 'مورد الهدف', 'phone' => '01155550000', 'balance' => 900]);
        Supplier::create(['name' => 'مورد آخر', 'phone' => '01166660000', 'balance' => 800]);
        Customer::create(['name' => 'مورد الهدف', 'phone' => '01155550000', 'balance' => 700]);

        $response = $this->get(route('debts.index', [
            'tab' => 'suppliers_due_to',
            'search' => '5555',
        ]));

        $response
            ->assertOk()
            ->assertSee('مورد الهدف')
            ->assertDontSee('مورد آخر')
            ->assertSee('name="tab" value="suppliers_due_to"', false);
    }

    public function test_ajax_requests_return_only_the_active_debt_content(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        Supplier::create(['name' => 'مورد أجاكس', 'phone' => '01200000000', 'balance' => 1500]);

        $response = $this->get(
            route('debts.index', ['tab' => 'suppliers_due_to', 'ajax' => 1]),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response
            ->assertOk()
            ->assertSee('مورد أجاكس')
            ->assertSee('المبلغ المستحق للمورد')
            ->assertDontSee('المديونيات والأرصدة');
    }
}
