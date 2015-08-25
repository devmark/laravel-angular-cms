<?php namespace App\Http\Controllers\Api;


use Input;
use Validator;
use Cache;
use DB;

use App\Models\MediaCategory;
use App\Transformers\MediaCategoryTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class MediaCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        // Get input data
        $validator = Validator::make(Input::all(), [

        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $categories = MediaCategory::simplePaginate(Input::get('limit', 50));

        return response()->paginator($categories, new MediaCategoryTransformer);

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = MediaCategory::find($id);
        $this->checkExist($category);

        return response()->item($category, new MediaCategoryTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $category = new MediaCategory;
        $category->name = Input::get('name');
        $category->save();

        return $this->show($category->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $category = MediaCategory::find($id);
        $this->checkExist($category);
        $category->name = Input::get('name');
        $category->save();

        return $this->show($category->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = MediaCategory::find($id);

        $this->checkExist($category);

        $category->delete();

        return response()->return();

    }

}
