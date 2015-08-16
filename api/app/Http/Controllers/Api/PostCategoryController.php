<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use Cache;
use DB;

use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\PostCategory;
use App\Transformers\PostCategoryTransformer;
use App\Helpers\HtmlHelper;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class PostCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        // Get input data
        $validator = Validator::make(Input::all(), [
            'ids'       => 'array|integerInArray',
            'parent_id' => 'integer',
            'search'    => 'max:255',
            'slug'      => 'alpha_dash',
            'name'      => 'max:255',
            'active'    => 'boolean'
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }
        $categories = new PostCategory;

        //Filter
        if (Input::has('search')) {
            $categories = $categories->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }

        if (Input::has('ids')) {
            $categories = $categories->whereIn('id', Input::get('ids'));
        }

        if (Input::has('name')) {
            $categories = $categories->where('name', 'like', Input::get('name') . '%');
        }
        if (Input::has('active')) {
            $categories = $categories->where('active', '=', Input::get('active') ? 1 : 0);
        }
        if (Input::has('parent_id')) {
            $categories = $categories->where('parent_id', '=', Input::get('parent_id'));
        }

        if (Input::has('slug')) {
            $categories = $categories->where('slug', '=', Input::get('slug'));
        }

        $categories = $categories->orderBy('lft', 'asc')->where('parent_id', '!=', 'null')->get();

        return response()->collection($categories, new PostCategoryTransformer);

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
        $category = PostCategory::find($id);

        if (is_null($category)) {
            throw new NotFoundException;
        }

        return response()->item($category, new PostCategoryTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'parent_id'        => 'exists:post_category,id',
            'active'           => 'boolean',
            'attachment'       => 'image',

            'slug'             => 'required|alpha_dash|Max:100',
            'name'             => 'min:1|max:100',
            'description'      => '',
            'meta_description' => '',
            'meta_title'       => '',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $category = new PostCategory;

            $fields = ['active', 'slug'];
            foreach ($fields as $key => $field) {
                if (Input::has($field)) {
                    $category->{$field} = Input::get($field);
                }
            }

            //field which can null/empty string
            $fields = ['description', 'name', 'meta_description', 'meta_title'];
            foreach ($fields as $key => $field) {
                if (Input::get($field) === '') {
                    $category->{$field} = null;
                } elseif (Input::has($field)) {
                    $category->{$field} = Input::get($field);
                }
            }

            $category->save();

            if (!Input::has('parent_id')) {
                $parentCategory = PostCategory::root();
            } else {
                $parentCategory = PostCategory::find(Input::get('parent_id'));
            }
            $category->makeChildOf($parentCategory);

            DB::commit();

            return $this->show($category->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

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
        //upload file
        $rules = [
            'parent_id'        => 'exists:post_category,id',
            'attributes'       => 'array|integerInArray|existsInArray:attribute,id',
            'active'           => 'boolean',
            'attachment'       => 'image|mimes:jpeg,bmp,png',

            'slug'             => 'alpha_dash|Max:100',
            'name'             => 'min:1|max:100',
            'description'      => '',
            'meta_description' => '',
            'meta_title'       => '',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $category = PostCategory::find($id);
        if (is_null($category)) {
            throw new NotFoundException;
        }

        DB::beginTransaction();
        try {

            $fields = ['active', 'slug'];
            foreach ($fields as $key => $field) {
                if (Input::has($field)) {
                    $category->{$field} = Input::get($field);
                }
            }

            //field which can null/empty string
            $fields = ['description', 'name', 'meta_description', 'meta_title'];
            foreach ($fields as $key => $field) {
                if (Input::get($field) === '') {
                    $category->{$field} = null;
                } elseif (Input::has($field)) {
                    $category->{$field} = Input::get($field);
                }
            }
            $category->save();

            if (Input::has('parent_id') && Input::get('parent_id') != $category->parent_id) {
                $parentCategory = PostCategory::find(Input::get('parent_id'));
                $category->makeChildOf($parentCategory);
            }

            DB::commit();

            return $this->show($category->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


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
        $category = PostCategory::find($id);
        if (is_null($category)) {
            throw new NotFoundException;
        }

        if ($category->isRoot()) {
            $this->postCategoryPresenter->errorDeleteRoot();
        }

        $category->delete();

        return response()->return();
    }

    /**
     * move resources
     *
     * @param  int $id
     *
     * @return Response
     */
    public function move($id)
    {
        $validator = Validator::make(Input::all(), [
            'parent_id' => 'exists:post_category,id',
        ]);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        if (is_null($category = PostCategory::find($id))) {
            return $this->postCategoryPresenter->errorNotFound();
        }


        if (Input::has('parent_id')) {
            $dest = PostCategory::find(Input::get('parent_id'));
        } else {
            $dest = PostCategory::root();
        }

        if (!is_null($next = Input::get('next_id'))) {
            $next = PostCategory::find(Input::get('next_id'));
        }
        if (!is_null($prev = Input::get('prev_id'))) {
            $prev = PostCategory::find(Input::get('prev_id'));
        }

        // dest = null, move to parent
        // dest = null, next = null, move to parent and last
        if (is_null($dest) && is_null($next) && !is_null($prev)) {
            //move to parent and last
            $category->moveToRightOf($prev);
        } else if (is_null($dest) && !is_null($next)) {
            //move to parent
            $category->moveToLeftOf($next);
        } else if (!is_null($dest) && is_null($next)) {
            $category->makeLastChildOf($dest);
        } else {
            //move to parent
            $category->moveToLeftOf($next);
        }

        return response()->return();


    }

}
