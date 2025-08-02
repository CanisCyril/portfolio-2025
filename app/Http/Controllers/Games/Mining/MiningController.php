<?php

namespace App\Http\Controllers\Games\Mining;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Games\Mining\Ore;
use App\Contracts\Game\MiningServiceInterface;

class MiningController extends Controller
{
    protected $miningService;

    public function __construct(MiningServiceInterface $miningService)
    {

        $this->miningService = $miningService;
    }

    public function index()
    {

        $user = User::with('wallets')->find(1);
        $ores = Ore::all();
        $userOres = $user->ores()->get();
        $mainWallet = $user->wallets->first();
        $equippedPickaxe = $user->equippedPickaxe->pickaxe->display_name;
        $level = $user->level->level ?? 1; // Default to level 1 if not set
        $userGold = $user->gold->amount ?? 0;

        return Inertia::render('Games/Mining', [
            'wallet' => $mainWallet,
            'ores' => $ores,
            'inventory' => $userOres,
            'equippedPickaxeName' => $equippedPickaxe,
            'level' => $level,
            'totalXp' => $user->level->total_xp ?? 0,
            'userGold' => $userGold,
        ]);

        // TIDY UP CONTOLLER
    }

    public function mine(Request $request)
    {

        // MOVE TO PROPER REQUEST FILE
        $request->validate([
           'oreID' => 'required|exists:ores,id',
        ]);

        $updatedData = $this->miningService->mineOre($request->oreID);
        // dd($updatedOre['items']);
        // dd($updatedInformation);

        $item = $updatedData['items'];
        $xp = $updatedData['xp'];



        return response()->json([
            'ore' => [
                'ore_id'          => $item->ore_id,
                'amount'          => $item->amount,
                'name'            => $item->ore->display_name,

            ],
            'experience' => [
                'gainedXp'        => $xp['gainedXp'],
                'totalCurrentXp'  => $xp['totalCurrentXp'],
            ],
        ]);
    }



}
