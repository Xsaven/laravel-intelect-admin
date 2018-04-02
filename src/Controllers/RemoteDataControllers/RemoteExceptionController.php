<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Addons\Reporter\ExceptionModel;


class RemoteExceptionController extends Controller{

    public function get(Request $request)
    {
        if(!is_null($request->list)){
            return response(\Help\ArrayClass::clone(ExceptionModel::all(),['name' => 'value']));
        }

        $obj = ExceptionModel::orderBy('id', 'desc');
        if(!is_null($request->met)) $obj->where('method', $request->met);
        if(!is_null($request->code)) $obj->where('code', $request->code);
        if(!is_null($request->type)) $obj->where('type', 'like', '%'.$request->type.'%');
        if(!is_null($request->message)) $obj->where('message', 'like', '%'.$request->message.'%');
        if(!is_null($request->path)) $obj->where('path', 'like', '%'.$request->path.'%');
        if(!is_null($request->que)) $obj->where('query', 'like', '%'.$request->que.'%');
        if(!is_null($request->created_at)) $obj->where('created_at', 'like', $request->created_at.'%');

        if(is_null($request->continue)){
            $exceptions = $obj->get()->toArray();
            $exceptions = $this->rebuildArray($exceptions);
            $return  = ['data' => array_slice($exceptions, 0, 30), 'pos' => 0, 'total_count' => count($exceptions)];
            return response($return);
        }
        $exceptions = $obj->skip($request->start)->take($request->count)->get()->toArray();
        $exceptions = $this->rebuildArray($exceptions);
        $return = ['data' => $exceptions, 'pos' => (int)$request->start];
        return response($return);
    }

    public function select(Request $request)
    {

    }

    private function rebuildArray($array){
        $colors = [
            'GET'    => 'green',
            'HEAD'   => 'gray',
            'POST'   => 'blue',
            'PUT'    => 'yellow',
            'DELETE' => 'red',
            'PATCH'  => 'aqua',
            'OPTIONS'=> 'light-blue'
        ];
        foreach ($array as $key => $val){
            $val['method'] = "<span class=\"label bg-{$colors[$val['method']]}\">{$val['method']}</span>";
            $val['trace'] = explode("\n", $val['trace']);
            $val['trace'] = str_replace('):', ")<br>:", $val['trace']);
            $array[$key] = $val;
        }
        return $array;
    }

    public function insert(Request $request)
    {
        $request->permissions = array_diff(explode(',', $request->permissions), array(''));
        $exceptions = ExceptionModel::create($request->all());
        if(count($request->permissions)) $exceptions->permissions()->attach($request->permissions);
        return response($exceptions, 201);
    }

    public function update(Request $request)
    {
        $req = $request->all();
        $req['permissions'] = array_diff(explode(',', $req['permissions']), array(''));
        $exceptions = ExceptionModel::updateOrCreate(['id' => $request->id], $req);
        $exceptions->permissions()->sync($req['permissions']);
        return response($exceptions, 202);
    }

    public function delete(Request $request)
    {
        if(!is_null($request->ids) && is_array($request->ids) && count($request->ids)){
            ExceptionModel::whereIn('id', $request->ids)->delete();
            return response(['status' => 'deleted']);
        }
    }

}