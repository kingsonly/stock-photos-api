<?php

namespace App\Http\Controllers\Api;

use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagNameResouorce;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /**$tags = QueryBuilder::for(Tags::class)
        ->allowedFilters('creator_id')
        ->defaultSort('-created_at')
        ->allowedSorts(['creator_id', 'created_at'])
        ->paginate();

        return new TagNameResouorce($tags);**/
        //return new TagCollection($tags);

        //return response()->json(Tags::all());
        return new TagCollection(Tags::all());

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
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        $tag = Tags::create($validated);
        

        return new TagNameResouorce($tag);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Tags $tag)
    {
        return new TagNameResouorce($tag);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tags $tags)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tags $tags)
    {
        $validated = $request->validated();
        $tags->update($validated);

        return new TagNameResouorce($tags);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Tags $tags)
    {
        $tags->delete();
        return response()->noContent();
    }
}
