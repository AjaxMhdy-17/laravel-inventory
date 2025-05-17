<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Traits\HandlesImageUploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TeamController extends Controller
{
    use HandlesImageUploads;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = Team::query();
            return DataTables::eloquent($users)
                ->addColumn('created_at', function ($user) {
                    return Carbon::parse($user->created_at)->format('Y-m-d');
                })
                ->addColumn('photo', function ($user) {
                    $imageUrl = isset($user->photo) ? asset($user->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('status', function ($user) {
                    return $user->active == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('action', function ($user) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.team.edit', $user->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.team.destroy', ['team' => $user->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'status', 'photo'])
                ->make(true);
        }
        // $data['user'] = Auth::user();
        $data['title'] = "Team Member";
        $data['teams'] = Team::all();
        return view('backend.team.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['user'] = Auth::user();
        $data['title'] = "Team Member Create";
        return view('backend.team.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateTeamInfo($request);
        if (isset($data['photo'])) {
            $imagePath = $this->uploadImage($data['photo'], 'upload/admin/team/', 340, 340);
            $data['photo'] = $imagePath;
        }
        Team::create($data);
        $notification = array(
            'message' => "Team Info Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.team.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $data['title'] = "Edit Team Member";
        $data['user'] = Auth::user();
        $data['team'] = Team::findOrFail($id);
        return view('backend.team.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $this->validateTeamInfo($request);
        $team = Team::findOrFail($id);
        if (isset($data['photo'])) {
            $this->deleteImage($team->photo);
            $imagePath = $this->uploadImage($data['photo'], 'upload/admin/team/', 340, 340);
            $data['photo'] = $imagePath;
        }
        isset($data['active']) ?  $data['active'] = 1 : $data['active'] = 0 ;
        $team->update($data);
        $notification = array(
            'message' => "Team Info Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.team.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);
        $this->deleteImage($team->photo);
        $team->delete();
        $notification = array(
            'message' => "Team Info Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.team.index')->with($notification);
    }

    public function bulkDelete(Request $request)
    {
        $idsString = $request->input('ids');
        $ids = array_filter(explode(',', $idsString));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No items selected for deletion.');
        }

        $teams = Team::whereIn('id', $ids)->get();
        foreach ($teams as $team) {
            $this->deleteImage($team->photo);
        }

        Team::whereIn('id', $ids)->delete();

        $notification = array(
            'message' => "Selected Team Members Deleted Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }



    


    protected function validateTeamInfo($request)
    {
        return $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'photo' => 'sometimes',
            'active' => 'sometimes', 
            'facebook' => 'sometimes',
            'linkedin' => 'sometimes',
            'tweeter' => 'sometimes',
            'github' => 'sometimes',
        ]);
    }
}
