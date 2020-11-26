<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Cabinet\Profile\UpdateRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        return view('cabinet.adverts.index');
    }

    public function show()
    {
        return view('cabinet.adverts.show');
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }

    public function update()
    {
        return redirect()->route('cabinet.advert');
    }

    public function destroy()
    {
        return redirect()->route('cabinet.advert');
    }
}
