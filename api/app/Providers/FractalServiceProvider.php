<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Fractal\Paginator\IlluminateSimplePaginatorAdapter;
use App\Fractal\Serializers\CustomArraySerializer;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $fractal = $this->app->make('League\Fractal\Manager');
        $fractal = $fractal->setSerializer(new CustomArraySerializer);

        $result = ['status' => true];

        response()->macro('return', function ($array = [], array $headers = []) use ($result) {

            $result = array_merge($result, $array);

            return response()->json(['result' => $result],
                200,
                $headers
            );
        });

        response()->macro('item', function ($item, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal, $result) {
            $resource = new \League\Fractal\Resource\Item($item, $transformer, null);

            $result['result'] = $fractal->createData($resource)->toArray();

            return response()->json($result,
                $status,
                $headers
            );
        });

        response()->macro('collection', function ($collection, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal, $result) {
            $resource = new \League\Fractal\Resource\Collection($collection, $transformer, null);

            $result['result'] = $fractal->createData($resource)->toArray();

            return response()->json($result,
                $status,
                $headers
            );
        });

        response()->macro('paginator', function ($collection, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal, $result) {

            $resource = new \League\Fractal\Resource\Collection($collection, $transformer, null);
            $resource->setPaginator(new IlluminateSimplePaginatorAdapter($collection));

            $result['result'] = $fractal->createData($resource)->toArray();
            $result['meta'] = $result['result']['meta'];
            unset($result['result']['meta']);

            return response()->json($result,
                $status,
                $headers
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
