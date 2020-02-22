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
     * Insert a new childnode into the nested set
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAChildNode(Request $request)
    {
        if(empty($request->name) || empty($request->parent)) {
            return response()->json(['error' => ['msg' => ['Failed to insert.']]], 404);
        }

        try {
            DB::beginTransaction();
            $parentCategory = Category::findOrFail(trim($request->parent));
            Category::where('rgt', '>', $parentCategory->lft)->update(['rgt' => DB::raw('rgt+2')]);
            Category::where('lft', '>', $parentCategory->lft)->update(['lft' => DB::raw('lft+2')]);

            $newCategory = new Category();
            $newCategory->name = $request->name;
            $newCategory->rgt = $parentCategory->lft + 2;
            $newCategory->lft = $parentCategory->lft + 1;
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
     * Remove a node and its all children from nested set.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(empty($request->id)) {
            return response()->json(['error' => ['msg' => ['Failed to insert.']]], 404);
        }

        try {
            DB::beginTransaction();
            $category = Category::findOrFail($request->id);
            $categoryWidth = $category->rgt - $category->lft + 1;

            Category::whereBetween('lft', [$category->lft, $category->rgt])->delete();
            Category::where('rgt', '>', $category->rgt)->update(['rgt' => DB::raw('rgt-'.$categoryWidth)]);
            Category::where('lft', '>', $category->rgt)->update(['lft' => DB::raw('lft-'.$categoryWidth)]);
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

        /*SELECT @myLeft := lft, @myRight := rgt, @myWidth := rgt - lft + 1
FROM nested_category
WHERE name = 'GAME CONSOLES';

DELETE FROM nested_category WHERE lft BETWEEN @myLeft AND @myRight;

UPDATE nested_category SET rgt = rgt - @myWidth WHERE rgt > @myRight;
UPDATE nested_category SET lft = lft - @myWidth WHERE lft > @myRight;*/
    }
}
