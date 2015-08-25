<?php namespace App\Http\Controllers\Api;


use Input;
use Validator;

use App\Models\Post;
use App\Transformers\PostTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class PostController extends ApiController
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
        $validator = Validator::make(Input::all(), [
            'ids'              => 'array|integerInArray',
            'page'             => 'integer',
            'published_at_min' => 'date_format:"Y-m-d H:i:s"',
            'published_at_max' => 'date_format:"Y-m-d H:i:s"',
            'created_at_min'   => 'date_format:"Y-m-d H:i:s"',
            'created_at_max'   => 'date_format:"Y-m-d H:i:s"',
            'updated_at_min'   => 'date_format:"Y-m-d H:i:s"',
            'updated_at_max'   => 'date_format:"Y-m-d H:i:s"',
            'limit'            => 'integer|min:1|max:250',
            'category_ids'     => 'array|integerInArray',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $posts = new Post;
        $posts = $posts->with([
            'tagged',
            'categories' => function ($query) {
                $query->select(['post_category_id']);
            },
        ])->orderBy('published_at', 'DESC');

        //Filter
        if (Input::has('search')) {
            $posts = $posts->where('title', 'LIKE', '%' . Input::get('search') . '%');
        }

        if (Input::has('ids')) {
            $posts = $posts->whereIn('id', Input::get('ids'));
        }

        if (Input::has('category_ids')) {
            $posts = $posts->whereHas('categories', function ($q) {
                $q->whereIn('id', Input::get('category_ids'));
            });
        }
        if (Input::has('published_at_min')) {
            $posts = $posts->where('published_at', '>=', Input::get('published_at_min'));
        }
        if (Input::has('published_at_max')) {
            $posts = $posts->where('published_at', '<=', Input::get('published_at_max'));
        }

        if (Input::has('created_at_min')) {
            $posts = $posts->where('created_at', '>=', Input::get('created_at_min'));
        }
        if (Input::has('created_at_max')) {
            $posts = $posts->where('created_at', '<=', Input::get('created_at_max'));
        }
        if (Input::has('updated_at_min')) {
            $posts = $posts->where('updated_at', '>=', Input::get('updated_at_min'));
        }
        if (Input::has('updated_at_max')) {
            $posts = $posts->where('updated_at', '<=', Input::get('updated_at_max'));
        }

        $posts = $posts->simplePaginate(Input::get('limit', 50));

        return response()->paginator($posts, new PostTransformer);

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
        $post = Post::find($id);

        $this->checkExist($post);

        $post = $post->load([
            'tagged',
            'categories' => function ($query) {
                $query->select(['post_category_id']);
            },
        ]);

        return response()->item($post, new PostTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'published_at' => 'date_format:"Y-m-d H:i:s"',
            'categories'   => 'array|not_inInArray:1|integerInArray|existsInArray:post_category,id',
            'status'       => 'in:published,draft,trash',
            'visibility'   => 'in:public,private',
            'tags'         => 'array|stringInAarray',
            'title'        => 'min:1|max:100',
            'slug'         => 'required|alpha_dash|min:1|max:100',
            'content'      => '',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }
        $post = new Post;

        $this->fillFieldFromInput($post, ['slug', 'status', 'visibility', 'published_at']);
        $this->fillNullableFieldFromInput($post, ['title', 'content']);

        $post->save();

        $post->categories()->sync(Input::get('categories', []));
        if (Input::has('tags')) {
            $post->retag(Input::get('tags'));
        }

        return $this->show($post->id);
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
            'published_at' => 'date_format:"Y-m-d H:i:s"',
            'status'       => 'in:published,draft,trash',
            'visibility'   => 'in:public,private',
            'categories'   => 'array|not_inInArray:1|integerInArray|existsInArray:post_category,id',
            'tags'         => 'array|stringInAarray',
            'title'        => 'min:1|max:100',
            'slug'         => 'alpha_dash|min:1|max:100',
            'content'      => '',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $post = Post::find($id);
        $this->checkExist($post);
        $this->fillFieldFromInput($post, ['slug', 'status', 'visibility', 'published_at']);
        $this->fillNullableFieldFromInput($post, ['title', 'content']);


        $post->save();

        $post->categories()->sync(Input::get('categories', []));
        if (Input::has('tags')) {
            $post->retag(Input::get('tags'));
        }

        return $this->show($id);
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
        $post = Post::find($id);
        $this->checkExist($post);

        $post->delete();

        return response()->return();

    }


}
