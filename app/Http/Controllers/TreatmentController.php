<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TreatmentController extends Controller
{
    public function show(string $slug): View
    {
        $treatments = config('treatments');

        abort_unless(array_key_exists($slug, $treatments), 404);

        return view('treatments.show', [
            'slug' => $slug,
            'treatment' => $treatments[$slug],
            'otherTreatments' => collect($treatments)->except($slug),
        ]);
    }
}
