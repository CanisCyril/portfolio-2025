<?php

namespace App\Http\Controllers\Games\Mining;

use App\Http\Controllers\Controller;
use App\Models\Games\Mining\Pickaxe;
use App\Models\Games\Mining\UserPickaxe;
use Illuminate\Http\Request;

class PickaxeController extends Controller
{
    public function index()
    {
        // Fetch all pickaxes from the database
        $player = auth()->user();

        $player->load('level');

        $playerLevel = $player->level->level ?? 1; // Default to level 1 if not set

        $unlockedPickaxes = Pickaxe::where('level_requirement', '<=', $playerLevel)->get();
        $nextLockedPickaxes = Pickaxe::where('level_requirement', '>', $playerLevel)->orderBy('level_requirement', 'asc')->first();
        $lockedPickaxes = Pickaxe::where('level_requirement', '>', $playerLevel)->whereNot('id', $nextLockedPickaxes->id)->pluck('id');

        // Return the pickaxes as a JSON response
        return response()->json([
            'unlockedPickaxes' => $unlockedPickaxes,
            'nextLockedPickaxe' => $nextLockedPickaxes,
            'lockedPickaxes' => $lockedPickaxes,
        ]);
    }

    public function equip(Request $request)
    {
        // Validate the request to ensure a pickaxe ID is provided
        $request->validate([
            'pickaxe_id' => 'required|exists:pickaxes,id',
        ]);

        // Find the pickaxe by ID
        // Incorrect use of whereIn removed; use where() instead if you want to filter by multiple conditions
        // Example of correct usage:
        $equipped = UserPickaxe::where([
            ['user_id', auth()->id()],
            ['equipped', true],
        ])->first();

        // If an equipped pickaxe is found, unequip it
        if ($equipped) {
            $equipped->equipped = false;
            $equipped->save();
        }

        // Find or create a user pickaxe entry for the authenticated user
        $userPickaxe = UserPickaxe::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'pickaxe_id' => $request->pickaxe_id
            ],
            [
                'equipped' => true
            ]
        );


        $userPickaxe->load('pickaxe');

        $equippedPickaxeName = $userPickaxe->pickaxe->display_name;
        return $equippedPickaxeName;


        // return response()->json(['message' => 'Pickaxe equipped successfully']);
    }

}
