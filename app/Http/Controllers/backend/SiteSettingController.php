<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\SiteSetting;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteSettingController extends Controller
{
    use HandlesImageUploads ; 

    public function index()
    {
        $data['user'] = Auth::user();
        $data['title'] = "Site Setting";
        $data['setting'] = SiteSetting::first();
        return view('backend.siteSetting.index', $data);
    }

    public function store(Request $request) {

        $data = $request->validate([
            'name' => 'required' , 
            'logo' => 'sometimes' , 
            'description' => 'required' , 
            'email' => 'required|email' , 
            'phone' => 'required' , 
            'street' => 'sometimes' , 
            'city' => 'sometimes' , 
            'country' => 'sometimes' , 
            'x' => 'sometimes' , 
            'facebook' => 'sometimes' , 
            'linkedin' => 'sometimes' , 
            'youtube' => 'sometimes' , 
            'instagram' => 'sometimes' , 
        ]);

        $setting = SiteSetting::first();


        if (!isset($setting)) {
            if(isset($data['logo'])){
                $imagePath = $this->uploadImage($data['logo'], 'upload/admin/logo/', 180, 180);
                $data['logo'] = $imagePath;
            }
            SiteSetting::create($data);
            $notification = array(
                'message' => "Site Setting Added Successfully !",
                'alert-type' => 'success'
            );
            return back()->with($notification);
        }

        if(isset($data['logo'])){

            if ($setting->logo && file_exists(public_path($setting->logo))) {
                $this->deleteImage($setting->logo) ; 
            }
            
            $imagePath = $this->uploadImage($data['logo'],'upload/admin/logo/',180,180) ; 
            $data['logo'] = $imagePath ; 
        }
        $setting->update($data);
        $notification = array(
            'message' => "Site Setting Updated Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);


    }
}
