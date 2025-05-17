<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['user'] = Auth::user();
        return view('backend.auth.profile.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['user'] = User::findOrFail($id);
        return view('backend.auth.profile.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "name" => 'required',
            "email" => "required",
            "photo" => 'sometimes'
        ]);

        $profile = User::findOrFail($id);



        if ($request->file("photo")) {
            $file = $request->file("photo");
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            if ($profile->photo && file_exists(public_path("upload/admin/photo/" . $profile->photo))) {
                unlink(public_path("upload/admin/photo/" . $profile->photo));
            }
            $file->move(public_path("upload/admin/photo"), $fileName);
            $profile['photo'] = $fileName;
        }



        $profile['name'] = $data['name'];
        $profile->save();
        $notification = array(
            'message' => "Profile Info Updated Successfully!",
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
