<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = auth()->guard('sanctum')->user();
        $album = Album::where(['user_id' => $user->id])->get();
        if (empty($album)) {
            return response()->json(["message" => "No Collection to show"], 200);
        }
        return response()->json(['user' => $user, 'album' => $album]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
        $validator = Validator::make($request->all(), [
            'album_name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => "ensure that all required filed are properly filled "], 400);
        }

        $user = auth()->guard('sanctum')->user();
        $checker = Album::where(['user_id' => $user->id, 'album_name' => $request->input('album_name')])->first();
        if (!empty($checker)){
            return response()->json([
                "status" => "error",
                "message" => "You already have a collection " . $request->input('album_name')
            ], 400);
        }

        $request->request->add(['user_id' => $user->id]);
        $album = Album::create($request->all());
        return response()->json(['status' => 'success', 'message' => "Collecton created successfully", 'data' => $album], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = auth()->guard('sanctum')->user();
        // $album = Album::find($id);
        $album = Album::where(['user_id' => $user->id, 'id' => $id])->first();
        if (empty($album)) {
            return response()->json(["message" => "No Collection to show"], 200);
        }
        return response()->json(['status' => 'success', 'message' => "Collecton found", 'data' => $album], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = auth()->guard('sanctum')->user();
        $album = Album::where(['user_id' => $user->id, 'id' => $id])->first();
        $album->update($request->all());
        return response()->json(['status' => 'success', 'message' => "Collecton found", 'data' => $album], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = auth()->guard('sanctum')->user();
        $checker = Album::where(['user_id' => $user->id, 'id' => $id])->first();
        if (empty($checker)) {
            return response()->json(["status" => "error", "message" => "There was no collection to delete"], 400);
        }
        $album = Album::destroy($id);
        return response()->json(['status' => 'success', 'message' => "Collecton deleted sucessfully", 'data' => $album], 201);
    }

    /**
     * Search the specified resource from storage.
     */
    public function search($search)
    {
        //
        $album = Album::where('album_name', 'like', '%'.$search.'%')->orwhere('description', 'like', '%'.$search.'%')->get();
        if (empty($album)) {
            return response()->json(["message" => "No Collection to show for ".$search], 200);
        }
        return  response()->json(['status' => 'success', 'message' => "Search result", 'data' => $album], 201);
    }
}
