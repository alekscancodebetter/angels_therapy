<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_customer_record(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.customers.store'), [
            'name' => 'Grace Taylor',
            'email' => 'GRACE@example.com',
            'phone' => '07123 456789',
            'notes' => 'Prefers lighter pressure.',
        ]);

        $customer = Customer::query()->where('email', 'grace@example.com')->firstOrFail();
        $response->assertRedirect(route('admin.customers.show', $customer));
        $this->assertSame('Prefers lighter pressure.', $customer->notes);
    }

    public function test_admin_can_search_and_update_customer_record(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create([
            'name' => 'Amelia Wood',
            'email' => 'amelia@example.com',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.customers.index', ['q' => 'Amelia']))
            ->assertOk()
            ->assertSee('Amelia Wood');

        $this->actingAs($admin)->put(route('admin.customers.update', $customer), [
            'name' => 'Amelia Woods',
            'email' => 'amelia@example.com',
            'phone' => '07000 000000',
            'notes' => '',
        ])->assertRedirect(route('admin.customers.show', $customer));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Amelia Woods',
            'phone' => '07000 000000',
        ]);
    }

    public function test_customer_content_is_escaped_in_admin_view(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create([
            'name' => '<script>alert(1)</script>',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.customers.show', $customer))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
