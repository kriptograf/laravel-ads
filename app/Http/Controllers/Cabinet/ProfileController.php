<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Profile\UpdateRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $profile = $user->profile;

        return view('cabinet.profile.index', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        return view('cabinet.profile.show', ['profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        return view('cabinet.profile.edit', ['profile' => $profile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Profile $profile)
    {
        $profile->update([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'location' => $request['location'],
        ]);

        return redirect()->route('cabinet.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        return view('cabinet.profile.destroy', ['profile' => $profile]);
    }
}
