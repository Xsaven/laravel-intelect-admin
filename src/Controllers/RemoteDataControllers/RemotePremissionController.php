<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Auth\Database\Permission;

class RemotePremissionController extends Controller{

    public function get(Request $request)
    {
        if(!is_null($request->list)){
            return response(\Help\ArrayClass::clone(Permission::all(),['name' => 'value']));
        }
    }

    public function delete(Request $request)
    {
        if(!is_null($request->ids) && is_array($request->ids) && count($request->ids)){
            Permission::whereIn('id', $request->ids)->delete();
            return response(['status' => 'deleted']);
        }
    }
}