<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        return Campaign::with('user')->get();
    }

    public function store(Request $request)
    {
        $campaign = Campaign::create($request->all());
        return response()->json($campaign);
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->ads()->delete();
        $campaign->delete();
        return response()->json(['message' => 'Кампания удалена']);
    }

    public function updateStatus(Request $request, Campaign $campaign, $status)
    {
        // Валидация статуса, если это необходимо
        if (!in_array($status, ['active', 'pending', 'archived'])) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        // Обновление статуса кампании
        $campaign->status = $status;
        $campaign->save();

        // Дополнительно: обновить статус всех объявлений в кампании
        $campaign->ads()->update(['status' => $status]);

        return response()->json(['message' => 'Статус кампании успешно обновлён']);
    }
}
