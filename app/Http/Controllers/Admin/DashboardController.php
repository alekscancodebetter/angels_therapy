<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now()->startOfDay();

        $stats = [
            'upcomingAppointments' => Appointment::query()->whereIn('status', Appointment::BLOCKING_STATUSES)->where('starts_at', '>=', $today)->count(),
            'customers' => Customer::query()->count(),
            'newCustomersThisMonth' => Customer::query()->whereBetween('created_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->count(),
        ];

        $todayAppointments = Appointment::query()
            ->with('customer')
            ->whereBetween('starts_at', [$today, $today->copy()->endOfDay()])
            ->orderBy('starts_at')
            ->get();

        $upcomingAppointments = Appointment::query()
            ->with('customer')
            ->whereIn('status', Appointment::BLOCKING_STATUSES)
            ->where('starts_at', '>=', $today)
            ->orderBy('starts_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'monthlyCustomers' => $this->monthlyCustomerCounts(),
        ]);
    }

    /** @return Collection<int, array{label: string, count: int, height: int}> */
    private function monthlyCustomerCounts(): Collection
    {
        $months = collect(range(5, 0))->map(function (int $monthsAgo): array {
            $month = now()->subMonths($monthsAgo);
            $count = Customer::query()->whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth(),
            ])->count();

            return [
                'label' => Carbon::parse($month)->format('M'),
                'count' => $count,
            ];
        });

        $maximum = max(1, (int) $months->max('count'));

        return $months->map(fn (array $month): array => [
            ...$month,
            'height' => max(8, (int) round(($month['count'] / $maximum) * 100)),
        ]);
    }
}
