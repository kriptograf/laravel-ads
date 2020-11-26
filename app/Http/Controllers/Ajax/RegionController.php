<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function get(Request $request)
    {
        $parent = $request->get('parent') ?: null;

        return Region::where('parent_id', $parent)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}
