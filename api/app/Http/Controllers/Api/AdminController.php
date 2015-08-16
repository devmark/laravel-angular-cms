<?php namespace App\Http\Controllers\Api;

use Input;
use Cache;
use Response;
use Validator;
use Config;

use App\Models\Admin;
use App\Transformers\AdminTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class AdminController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $admins = Admin::get();

        $admins = $admins->simplePaginate(Input::get('limit', 50));

        return response()->paginator($admins, new AdminTransformer);

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

        $admin = Admin::find($id);
        if (is_null($admin)) {
            throw new NotFoundException;
        }

        return response()->item($admin, new AdminTransformer);

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
