<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\GameBroadcastingEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index()
    {
        $monsters = Cache::get('game.monsters', []);

        return compact('monsters');
    }

    public function loc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'x' => ['required', 'int'],
            'y' => ['required', 'int'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()])->setStatusCode(202);
        }

        $user = auth()->user();

        $gameData = $request->only('x', 'y');

        Cache::set("game.{$user->id}", $gameData);

        broadcast(new GameBroadcastingEvent($gameData))->toOthers();
    }

    public function createMonster()
    {
        $monster = $this->monsterOnPlace();
        $monsters = Cache::get('game.monsters', []);
        $monsters[] = $monster;
        Cache::set('game.monsters', $monsters);
        $id = Cache::increment('game.monster.id');
        broadcast(new GameBroadcastingEvent(compact('id', 'monster')));
    }

    public function monsterOnPlace()
    {
        $places = [];

        $layers = config('game.layers');
        foreach ($layers[1] as $x => $rows) {
            foreach ($rows as $y => $column) {
                if (in_array($column, [4, 5], true)) {
                    continue;
                }

                $places[] = ['x' => $x, 'y' => $y];
            }
        }

        $count = count($places);

        $rand = random_int(0, $count - 1);

        return $places[$rand];
    }
}