<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use Cache;

use App\Helpers\HtmlHelper;
use App\Helpers\StrHelper;
use Illuminate\Support\Str;

class HelperController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function slug()
    {
        $slug = '';
        if (Input::has('slug')) {
            $slug = StrHelper::slug(Input::get('slug'));
        }

        return response()->return(['slug' => $slug]);
    }


}
