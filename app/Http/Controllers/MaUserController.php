<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use App\MaUser;
use DataTables;

class MaUserController extends Controller
{
    public function index()
    {
        $data = MaUser::paginate(10)->onEachSide(5);
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'username' => 'required|unique:ma_users',
            'role' => 'in:admin,user',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   

        $data = new MaUser;
        $data->id =  Uuid::uuid1()->toString();
        $data->nama_lengkap = $request->nama_lengkap;
        $data->username = $request->username;
        $data->password = bcrypt($request->username);
        $data->role = $request->role;
        $data->status = 'AKTIF';
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $data = MaUser::find($id);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'role' => 'in:admin, user',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   

        
        $data = MaUser::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => null
            ], 404);
        }

        $data->nama_lengkap = $request->nama_lengkap;
        $data->role = $request->role;
        $data->update();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = MaUser::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
