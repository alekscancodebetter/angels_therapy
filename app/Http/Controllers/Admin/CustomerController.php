<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $customers = Customer::query()
            ->withCount('appointments')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create([
            ...$request->validated(),
            'email' => $this->normalizedEmail($request),
        ]);

        return redirect()->route('admin.customers.show', $customer)->with('status', 'Customer added successfully.');
    }

    public function show(Customer $customer): View
    {
        $appointments = $customer->appointments()->latest('starts_at')->paginate(10);

        return view('admin.customers.show', compact('customer', 'appointments'));
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update([
            ...$request->validated(),
            'email' => $this->normalizedEmail($request),
        ]);

        return redirect()->route('admin.customers.show', $customer)->with('status', 'Customer details updated.');
    }

    private function normalizedEmail(Request $request): ?string
    {
        $email = Str::lower($request->string('email')->trim()->toString());

        return $email !== '' ? $email : null;
    }
}
