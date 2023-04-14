<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(User $user, Request $request)
    {   
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
            /*$request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ])
            */
            //'password' => Hash::make($request['password'])
        ]);

        return $this->success('profile','Profile updated successfully!');
    }

    public function updatePassword(User $user, Request $request)
    {   
        $user->update([
            
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]),
            'password' => Hash::make($request['password'])
        ]);

        return $this->success('profile','Votre mot de passe à été mis à jour !');
        //session()->flash('success', 'Your password updated successfully!');
    }

    public function success(){
        session()->flash('success', 'Votre profil à été mis à jour !');
        return redirect()->route('profile');
    }
}