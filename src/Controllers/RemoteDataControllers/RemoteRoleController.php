<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Auth\Database\Role;

class RemoteRoleController extends Controller{

    public function get(Request $request)
    {
        if(!is_null($request->list)){
            return response(\Help\ArrayClass::clone(Role::all(),['name' => 'value']));
        }

        $obj = Role::orderBy('id', 'desc')->with('permissions');
        if(!is_null($request->na)) $obj->where('name', 'like', $request->na.'%');
        if(!is_null($request->slug)) $obj->where('slug', $request->slug);
        if(!is_null($request->created_at)) $obj->where('created_at', 'like', $request->created_at.'%');
        if(!is_null($request->updated_at)) $obj->where('updated_at', 'like', $request->updated_at.'%');

        if(is_null($request->continue)){
            $roles = $obj->get()->toArray();
            foreach($roles as $key => $val) $roles[$key]['permissions'] = implode(',',\Help\ArrayClass::convert($roles[$key]['permissions'],'id','id'));
            $return  = ['data' => array_slice($roles, 0, 30), 'pos' => 0, 'total_count' => count($roles)];
            return response($return);
        }
        $roles = $obj->skip($request->start)->take($request->count)->get()->toArray();
        foreach($roles as $key => $val) $roles[$key]['permissions'] = implode(',',\Help\ArrayClass::convert($roles[$key]['permissions'],'id','id'));
        $return = ['data' => $roles, 'pos' => (int)$request->start];
        return response($return);
    }

    public function insert(Request $request)
    {
        $request->permissions = array_diff(explode(',', $request->permissions), array(''));
        $role = Role::create($request->all());
        if(count($request->permissions)) $role->permissions()->attach($request->permissions);
        return response($role, 201);
    }

    public function update(Request $request)
    {
        $req = $request->all();
        $req['permissions'] = array_diff(explode(',', $req['permissions']), array(''));
        $role = Role::updateOrCreate(['id' => $request->id], $req);
        $role->permissions()->sync($req['permissions']);
        return response($role, 202);
    }

    public function delete(Request $request)
    {
        if(!is_null($request->ids) && is_array($request->ids) && count($request->ids)){
            Role::whereIn('id', $request->ids)->delete();
            return response(['status' => 'deleted']);
        }
    }
}