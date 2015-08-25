<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\NotFoundException;
use Input;

class ApiController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * check model exist
     *
     * @param  Model $obj
     *
     * @return void
     */
    public function checkExist($obj)
    {
        if (is_null($obj)) {
            throw new NotFoundException;
        }
    }

    /**
     * fill nullable field from input
     *
     * @param  Model $obj
     * @param  array $fields
     *
     * @return void
     */
    public function fillNullableFieldFromInput($obj, $fields = [])
    {
        foreach ($fields as $key => $field) {
            if (Input::get($field) === '') {
                $obj->{$field} = null;
            } elseif (Input::has($field)) {
                $obj->{$field} = Input::get($field);
            }
        }

    }

    /**
     * fill field from input
     *
     * @param  Model $obj
     * @param  array $fields
     *
     * @return void
     */
    public function fillFieldFromInput($obj, $fields = [])
    {
        foreach ($fields as $key => $field) {
            if (Input::has($field)) {
                $obj->{$field} = Input::get($field);
            }
        }

    }
}