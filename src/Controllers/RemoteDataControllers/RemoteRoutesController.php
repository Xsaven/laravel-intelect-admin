<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RemoteRoutesController extends Controller{

    public function get(Request $request)
    {
        $colors = [
            'GET'    => 'green',
            'HEAD'   => 'gray',
            'POST'   => 'blue',
            'PUT'    => 'yellow',
            'DELETE' => 'red',
            'PATCH'  => 'aqua',
            'OPTIONS'=> 'light-blue'
        ];

        $routes = $this->getRoutes();

        foreach($routes as $key => $route){
            foreach ($route['method'] as $mKey => $method) {
                $route['method'][$mKey] = "<span class=\"label bg-{$colors[$method]}\">$method</span>";
            }
            $route['method'] = implode(' ', $route['method']);

            $route['middleware'] = $route['middleware']->toArray();

            foreach ($route['middleware'] as $mKey => $middleware) {
                $route['middleware'][$mKey] = "<span class=\"label bg-yellow\">$middleware</span>";
            }
            $route['middleware'] = implode(' ', $route['middleware']);

            $routes[$key] = $route;
        }

        if(!is_null($request->host))
            foreach ($routes as $key => $val){
                if(!str_contains($val['host'], $request->host))
                    unset($routes[$key]);
            }

        if(!is_null($request->met))
            foreach ($routes as $key => $val){
                if(!str_contains($val['method'], $request->met))
                    unset($routes[$key]);
            }

        if(!is_null($request->uri))
            foreach ($routes as $key => $val){
                if(!str_contains($val['uri'], $request->uri))
                    unset($routes[$key]);
            }

        if(!is_null($request->na))
            foreach ($routes as $key => $val){
                if(!str_contains($val['name'], $request->na))
                    unset($routes[$key]);
            }

        if(!is_null($request->action))
            foreach ($routes as $key => $val){
                if(!str_contains($val['action'], $request->action))
                    unset($routes[$key]);
            }

        if(!is_null($request->middleware))
            foreach ($routes as $key => $val){
                if(!str_contains($val['middleware'], explode(' ', $request->middleware)))
                    unset($routes[$key]);
            }

        if(is_null($request->continue)){
            $return  = ['data' => array_slice($routes, 0, 30), 'pos' => 0, 'total_count' => count($routes)];
            return response($return);
        }

        $return = ['data' => array_slice($routes, $request->start, $request->count), 'pos' => (int)$request->start];
        return response($return);
    }

    public function getRoutes()
    {
        $routes = app('router')->getRoutes();

        $routes = collect($routes)->map(function ($route) {
            return $this->getRouteInformation($route);
        })->all();

        if ($sort = request('_sort')) {
            $routes = $this->sortRoutes($sort, $routes);
        }

        return array_filter($routes);
    }

    protected function getRouteInformation(Route $route)
    {
        return [
            'host'       => $route->domain(),
            'method'     => $route->methods(),
            'uri'        => $route->uri(),
            'name'       => $route->getName(),
            'action'     => $route->getActionName(),
            'middleware' => $this->getRouteMiddleware($route),
        ];
    }

    protected function getRouteMiddleware($route)
    {
        return collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof \Closure ? 'Closure' : $middleware;
        });
    }

}