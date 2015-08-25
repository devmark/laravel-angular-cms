<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use DB, Storage, File, Response;

use App\Models\Media;
use App\Transformers\MediaTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class MediaController extends ApiController
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
            'page'             => 'integer',
            'published_at_min' => 'date_format:"Y-m-d H:i:s"',
            'published_at_max' => 'date_format:"Y-m-d H:i:s"',
            'created_at_min'   => 'date_format:"Y-m-d H:i:s"',
            'created_at_max'   => 'date_format:"Y-m-d H:i:s"',
            'updated_at_min'   => 'date_format:"Y-m-d H:i:s"',
            'updated_at_max'   => 'date_format:"Y-m-d H:i:s"',
            'limit'            => 'integer|min:1|max:250',
            'search'           => 'max:255',
            'category_ids'     => 'array|integerInArray',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $media = new Media;

        //Filter
        if (Input::has('category_ids')) {
            $media = $media->whereIn('media_category_id', Input::get('category_ids'));
        }
        if (Input::has('search')) {
            $media = $media->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }
        if (Input::has('created_at_min')) {
            $media = $media->where('created_at', '>=', Input::get('created_at_min'));
        }
        if (Input::has('created_at_max')) {
            $media = $media->where('created_at', '<=', Input::get('created_at_max'));
        }
        if (Input::has('updated_at_min')) {
            $media = $media->where('updated_at', '>=', Input::get('updated_at_min'));
        }
        if (Input::has('updated_at_max')) {
            $media = $media->where('updated_at', '<=', Input::get('updated_at_max'));
        }

        $media = $media->simplePaginate(Input::get('limit', 50));

        return response()->paginator($media, new MediaTransformer);
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
        $media = Media::find($id);

        $this->checkExist($media);

        return response()->item($media, new MediaTransformer);
    }


    public function get($type, $yearAndMonth, $day, $filename)
    {

        $media = Media::where('key', '=', $filename)->first();
        if (!is_null($media)) {
            $file = Storage::get('uploads/' . $type . '/' . $yearAndMonth . '/' . $day . '/' . $media->key);
            if ($file) {
                return Response($file, 200)->header('Content-Type', $media->mime);
            }
        }

        throw new NotFoundException;
    }


    private function generateDateFolder()
    {
        $date = date('Y-m-d');
        $dateFolder = date('Y', strtotime($date)) . date('m', strtotime($date)) . '/' . date('d', strtotime($date)) . '/';

        return $dateFolder;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'attachment'        => 'required|mimes:jpeg,png,pdf,docx,xlsx,pptx,txt,zip,rar|max:10000',
            'name'              => 'max:255',
            'media_category_id' => 'exists:media_category,id'
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $media = new Media;
            if (Input::has('media_category_id')) {
                $media->media_category_id = Input::get('media_category_id');
            }
            $media->name = Input::get('name', '');
            $file = Input::file('attachment');
            $extension = $file->getClientOriginalExtension();
            $media->filesize = $file->getSize();
            $media->mime = $file->getClientMimeType();
            $media->key = strtolower(md5(uniqid($media->id . rand()))) . '.' . $extension;
            $media->path = $this->generateDateFolder();

            //upload file
            Storage::put('uploads/m/' . $media->path . $media->key, File::get($file));
            $media->save();
            DB::commit();

            return $this->show($media->id);

        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException('Invalid request parameters');
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
        $rules = [
            'name'              => 'max:255',
            'media_category_id' => 'exists:media_category,id'
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $media = Media::find($id);
        $this->checkExist($media);
        if (Input::has('media_category_id')) {
            $media->media_category_id = Input::get('media_category_id');
        }
        if (Input::has('name')) {
            $media->name = Input::get('name');
        }
        $media->save();

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
        $media = Media::find($id);
        $this->checkExist($media);

        $media->delete();

        return response()->return();

    }

}
