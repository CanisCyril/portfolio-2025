<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Games\Mining\Ore;
use App\Models\Games\Mining\UserOre;
use App\Contracts\Game\MiningServiceInterface;

class MiningController extends Controller
{
    protected $miningService;

    public function __construct(MiningServiceInterface $miningService)  {
        
        $this->miningService = $miningService;
    }

    public function index() {

        $user = User::with('wallets')->find(1);
        $ores = Ore::all();
        $userOres = UserOre::with('ore')->get();
        $mainWallet = $user->wallets->first();

        return Inertia::render('Games/Mining', [
            'wallet' => $mainWallet,
            'ores' => $ores,
            'inventory' => $userOres,
        ]);
    }

    public function mine(Request $request) { 

        // MOVE TO PROPER REQUEST FILE
         $request->validate([
            'oreID' => 'required|exists:ores,id',
        ]);

        $updatedInformation = $this->miningService->mineOre($request->oreID);
        // dd($updatedOre['items']);

        return response()->json([
            'updated_ore' => [
                'ore_id' => $updatedInformation['items']->ore_id,
                'amount' => $updatedInformation['items']->amount,
                'name' => $updatedInformation['items']->ore->display_name,
            ]
        ]);
    }



}
