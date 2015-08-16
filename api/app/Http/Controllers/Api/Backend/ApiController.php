<?php namespace App\Http\Controllers\Api\Backend;

use View, Input, Validator;
use Auth, User;
use Redirect;

class ApiController extends \App\Http\Controllers\Api\ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

}
