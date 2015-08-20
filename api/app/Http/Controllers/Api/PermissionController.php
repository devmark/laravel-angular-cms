<?php namespace App\Http\Controllers\Api;

use Input;
use Cache;
use Response;
use Validator;
use Config;

use App\Models\Permission;
use App\Transformers\PermissionTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class PermissionController extends ApiController
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

        $permissions = new Permission;

        $permissions = $permissions->simplePaginate(Input::get('limit', 50));

        return response()->paginator($permissions, new PermissionTransformer);

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
        $permission = Permission::find($id);
        if (is_null($permission)) {
            throw new NotFoundException;
        }

        return response()->item($permission, new PermissionTransformer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

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

    }

}
