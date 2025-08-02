<?php

namespace App\Http\Controllers\Games\Mining;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Games\Mining\UserGold;
use App\Models\Games\Mining\UserOre;
use App\Models\Games\Mining\Ore;

class UserGoldController extends Controller
{
    /**
     * Display the user's current gold amount.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $userId)
    {
        $userGold = UserGold::where('user_id', $userId)->first();

        if (!$userGold) {
            return response()->json(['error' => 'User gold not found'], 404);
        }

        return response()->json(['amount' => $userGold->getAmount()]);
    }

    /**
     * Add gold to the user's balance.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function sellAll(Request $request)
    {

    

        $userId = auth()->id();

        $userOres = UserOre::where('user_id', $userId)->with('ore')->get();

        $totalValue = 0;

        foreach ($userOres as $userOre) {

            $value = $userOre->ore->value * $userOre->amount;
            $totalValue += $value;

            // $userOre->delete();
        }

        if ($totalValue <= 0) {
            return response()->json(['error' => 'You are too poor, kind sir.'], 400);
        }

        $userGold = UserGold::firstOrCreate(['user_id' => $userId]);
        $userGold->addGold($totalValue);

        $totalGold = $userGold->getAmount();

        return response()->json(['totalGold' => $totalGold, 'message' => 'Gold added successfully.']);
    }
}
