<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Like;
use App\Models\Link;
use App\Models\Quote;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class MyStuffController extends Controller
{
    public function aboutMe(): array
    {
        $newData = [];

        $data = collect(About::all())->groupBy('category')->toArray();

        foreach ($data as $key => $value) {
            $newData[] = [
                'category' => $key,
                'list' => $value,
            ];
        }

        return $newData;
    }

    public function Likes(): Collection|array
    {
        return Like::all();
    }

    public function quotes(): Collection|array
    {
        return Quote::all();
    }

    public function quote()
    {
        return Quote::all()->random(1)->first()->content;
    }

    public function others()
    {
        return [
            'doings' => Task::where('is_doing', 1)->pluck('title'),
            'links' => Link::all(['title', 'url']),
        ];
    }
}
