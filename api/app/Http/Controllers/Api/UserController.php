<?php namespace App\Http\Controllers\Api;

use Input;
use Cache;
use Response;
use Validator;
use Config;

use App\Models\User;
use App\Transformers\UserTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class UserController extends ApiController
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
            'ids'            => 'array|integerInArray',
            'page'           => 'integer',
            'created_at_min' => 'date_format:"Y-m-d H:i:s"',
            'created_at_max' => 'date_format:"Y-m-d H:i:s"',
            'updated_at_min' => 'date_format:"Y-m-d H:i:s"',
            'updated_at_max' => 'date_format:"Y-m-d H:i:s"',
            'limit'          => 'integer|min:1|max:250',
            'search'         => 'string',
            'role_ids'       => 'array|integerInArray'
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $users = new User;
        if (Input::has('ids')) {
            $users = $users->whereIn('id', Input::get('ids'));
        }
        //Filter
        if (Input::has('search')) {
            $users = $users->where('lastname', 'LIKE', '%' . Input::get('search') . '%')->orWhere('firstname', 'LIKE', '%' . Input::get('search') . '%');
        }

        if (Input::has('role_ids')) {
            $users = $users->whereHas('roles', function ($q) {
                $q->whereIn('id', Input::get('role_ids'));
            });
        }
        if (Input::has('created_at_min')) {
            $users = $users->where('created_at', '>=', Input::get('created_at_min'));
        }
        if (Input::has('created_at_max')) {
            $users = $users->where('created_at', '<=', Input::get('created_at_max'));
        }

        $users = $users->simplePaginate(Input::get('limit', 50));

        return response()->paginator($users, new UserTransformer);

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

        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundException;
        }

        return response()->item($user, new UserTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'lastname'  => 'string|min:1|max:255',
            'firstname' => 'string|min:1|max:255',
            'active'    => 'boolean',
            'email'     => 'required|email',
            'password'  => 'required|min:8|max:255',
            'roles'     => 'array|integerInArray|existsInArray:role,id',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }
        $user = new User;

        $fields = ['active', 'email', 'password'];
        foreach ($fields as $key => $field) {
            if (Input::has($field)) {
                $user->{$field} = Input::get($field);
            }
        }

        //field which can null/empty string
        $fields = ['lastname', 'firstname'];
        foreach ($fields as $key => $field) {
            if (Input::get($field) === '') {
                $user->{$field} = null;
            } elseif (Input::has($field)) {
                $user->{$field} = Input::get($field);
            }
        }
        $user->save();

        $user->roles()->sync(Input::get('roles', []));

        return $this->show($user->id);
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
            'lastname'  => 'string|min:1|max:255',
            'firstname' => 'string|min:1|max:255',
            'active'    => 'boolean',
            'email'     => 'email',
            'password'  => 'min:8|max:255',
            'roles'     => 'array|integerInArray|existsInArray:role,id',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }
        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundException;
        }

        $fields = ['active', 'email', 'password'];
        foreach ($fields as $key => $field) {
            if (Input::has($field)) {
                $user->{$field} = Input::get($field);
            }
        }

        //field which can null/empty string
        $fields = ['lastname', 'firstname'];
        foreach ($fields as $key => $field) {
            if (Input::get($field) === '') {
                $user->{$field} = null;
            } elseif (Input::has($field)) {
                $user->{$field} = Input::get($field);
            }
        }
        $user->save();

        if (Input::has('roles')) {
            $user->roles()->sync(Input::get('roles', []));
        }

        return $this->show($user->id);
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
        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundException;
        }

        $user->delete();

        return response()->return();
    }

}
