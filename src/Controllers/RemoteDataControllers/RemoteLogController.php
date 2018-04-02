<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Auth\Database\Administrator;
use Lia\Auth\Database\OperationLog;

class RemoteLogController extends Controller{

    public function get(Request $request)
    {
        if(!is_null($request->selectListAdmin)) {
            $admin = \Help\ArrayClass::clone(Administrator::select('id', 'name')->get(), ['name' => 'value'], true);
            return response(array_merge([['id' => 'all', 'value' => 'All admins']],$admin));
        }

        $obj = OperationLog::orderBy('id', 'desc');
        if(!is_null($request->met)) $obj->where('method', $request->met);
        if(!is_null($request->ip)) $obj->where('ip', 'like', $request->ip.'%');
        if(!is_null($request->user_id)) $obj->where('user_id', $request->user_id);
        if(!is_null($request->path)) $obj->where('path', 'like', '%'.$request->path.'%');
        if(!is_null($request->data)) $obj->where('input', 'like', '%'.$request->data.'%');
        if(!is_null($request->date)) $obj->where('created_at', 'like', $request->date.'%');

        if(is_null($request->continue)){
            $logs = $obj->get()->toArray();
            $logs = $this->rebuildArray($logs);
            $return  = ['data' => array_slice($logs, 0, 30), 'pos' => 0, 'total_count' => count($logs)];
            return response($return);
        }
        $log = $obj->skip($request->start)->take($request->count)->get();
        $log = $this->rebuildArray($log);
        $return = ['data' => $log, 'pos' => (int)$request->start];
        return response($return);
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
            $array[$key] = $val;
        }
        return $array;
    }

    public function delete(Request $request)
    {
        if(!is_null($request->ids) && is_array($request->ids) && count($request->ids)){
            OperationLog::whereIn('id', $request->ids)->delete();
            return response(['status' => 'deleted']);
        }
    }

}