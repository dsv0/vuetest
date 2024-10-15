<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function index()
    {
        return Ads::with('campaign')->get();
    }

    public function store(Request $request)
    {
        $ad = Ads::create($request->all());
        return response()->json($ad);
    }

    public function destroy(Ads $ad)
    {
        $ad->delete();
        return response()->json(['message' => 'Объявление удалено']);
    }

    public function updateBudget(Ads $ad, Request $request)
    {
        $newBudget = $request->input('budget');
        $ad->update(['budget' => $newBudget]);
        if ($newBudget > 0) {
            $ad->update(['status' => 'active']);
        }
        return response()->json(['message' => 'Бюджет и статус обновлены']);
    }

    public function updateContent(Ads $ad, Request $request)
    {
        $ad->update(['content' => $request->input('content')]);
        $ad->update(['status' => 'pending']);
        \Illuminate\Support\Facades\Cache::put("ad_{$ad->id}_pending", true, now()->addMinutes(3));

        return response()->json(['message' => 'Обновлено. Статус "в ожидании"']);
    }
}
