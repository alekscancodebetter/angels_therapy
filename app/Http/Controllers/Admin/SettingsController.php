<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\MarketingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'marketingSettings' => MarketingSetting::query()->first() ?? new MarketingSetting([
                'tracking_mode' => config('google_tracking.mode'),
                'google_tag_id' => config('google_tracking.tag_id'),
                'google_analytics_measurement_id' => config('google_tracking.analytics_measurement_id'),
                'google_tag_manager_container_id' => config('google_tracking.tag_manager_container_id'),
                'google_ads_tag_id' => config('google_ads.tag_id'),
                'google_ads_booking_conversion_label' => config('google_ads.booking_conversion_label'),
                'google_ads_booking_conversion_value' => config('google_ads.booking_conversion_value'),
                'google_ads_booking_conversion_currency' => config('google_ads.booking_conversion_currency'),
                'google_ads_phone_conversion_label' => config('google_ads.phone_conversion_label'),
                'google_ads_phone_conversion_value' => config('google_ads.phone_conversion_value'),
                'google_ads_phone_conversion_currency' => config('google_ads.phone_conversion_currency'),
            ]),
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $data = Arr::only($request->validated(), ['name', 'email']);
        $data['email'] = Str::lower($request->string('email')->trim()->toString());

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }

        $request->user()->update($data);

        $marketingData = Arr::except($request->validated(), ['name', 'email', 'password']);

        foreach ($marketingData as $key => $value) {
            if (is_string($value)) {
                $marketingData[$key] = $value === '' ? null : trim($value);
            }
        }

        foreach ([
            'google_tag_id',
            'google_analytics_measurement_id',
            'google_tag_manager_container_id',
            'google_ads_tag_id',
            'google_ads_booking_conversion_currency',
            'google_ads_phone_conversion_currency',
        ] as $uppercaseField) {
            if (isset($marketingData[$uppercaseField])) {
                $marketingData[$uppercaseField] = Str::upper($marketingData[$uppercaseField]);
            }
        }

        MarketingSetting::query()->updateOrCreate(['id' => 1], $marketingData);

        return back()->with('status', 'Admin and Google tracking settings updated.');
    }
}
