<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json(['product_categories' => $categories], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Insert a new node into the nested set
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request->name) || empty($request->after)) {
            return response()->json(['error' => ['msg' => ['Failed to insert.']]], 404);
        }

        try {
            DB::beginTransaction();
            $afterCategory = Category::findOrFail(trim($request->after));
            Category::where('rgt', '>', $afterCategory->rgt)->update(['rgt' => DB::raw('rgt+2')]);
            Category::where('lft', '>', $afterCategory->rgt)->update(['lft' => DB::raw('lft+2')]);

            $newCategory = new Category();
            $newCategory->name = $request->name;
            $newCategory->rgt = $afterCategory->rgt + 2;
            $newCategory->lft = $afterCategory->rgt + 1;
            $newCategory->saveOrFail();
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ['msg' => [$e->getMessage()]]], 404);
        } catch(\Throwable $t) {
            DB::rollBack();
            return response()->json(['error' => ['msg' => [$t->getMessage()]]], 404);
        }

        return response()->json([
            'success' => true,
            'msg' => $request->name . " category was inserted."], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        return response()->json(['product_category' => $category], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
