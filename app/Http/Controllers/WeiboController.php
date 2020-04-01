<?php

namespace App\Http\Controllers;

use App\Models\WeiboHot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeiboController extends Controller
{
    public function index()
    {
        $date = request('date');
        $query = WeiboHot::query();
        if ($date) {
            $query->whereDate('updated_at', $date);
        } else {
            $query->whereDate('updated_at', Carbon::today());
        }
        $query->orderBy('updated_at', 'DESC');

        return $query->jsonPaginate();
    }

    public function about()
    {
        return [
            'total' => WeiboHot::count(),
            'startDate' => WeiboHot::min('created_at'),
            'endDate' => WeiboHot::max('created_at'),
        ];
    }
}
