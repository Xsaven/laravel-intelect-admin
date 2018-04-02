<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Auth\Database\Administrator;
use Lia\Auth\Database\Permission;
use Lia\Auth\Database\Role;

class RemoteAdminController extends Controller{

    public function get(Request $request)
    {
        //$request->photo->store('images')
        if(!is_null($request->list)){
            return response(\Help\ArrayClass::clone(Administrator::all(),['name' => 'value']));
        }

        $obj = Administrator::orderBy('id', 'desc')->with('permissions', 'roles');
        if(!is_null($request->username)) $obj->where('username', 'like', $request->username.'%');
        if(!is_null($request->na)) $obj->where('name', 'like', $request->na.'%');
        if(!is_null($request->created_at)) $obj->where('created_at', 'like', $request->created_at.'%');
        if(!is_null($request->updated_at)) $obj->where('updated_at', 'like', $request->updated_at.'%');

        if(is_null($request->continue)){
            $admin = $obj->get()->toArray();
            foreach($admin as $key => $val) $admin[$key]['permissions'] = implode(',',\Help\ArrayClass::convert($admin[$key]['permissions'],'id','id'));
            foreach($admin as $key => $val) $admin[$key]['roles'] = implode(',',\Help\ArrayClass::convert($admin[$key]['roles'],'id','id'));
            foreach($admin as $key => $val) $admin[$key]['password'] = '';
            $return  = ['data' => array_slice($admin, 0, 30), 'pos' => 0, 'total_count' => count($admin)];
            return response($return);
        }
        $admin = $obj->skip($request->start)->take($request->count)->get()->toArray();
        foreach($admin as $key => $val) $admin[$key]['permissions'] = implode(',',\Help\ArrayClass::convert($admin[$key]['permissions'],'id','id'));
        foreach($admin as $key => $val) $admin[$key]['roles'] = implode(',',\Help\ArrayClass::convert($admin[$key]['roles'],'id','id'));
        foreach($admin as $key => $val) $admin[$key]['password'] = '';
        $return = ['data' => $admin, 'pos' => (int)$request->start];
        return response($return);
    }

    public function insert(Request $request)
    {
        if(!is_null($request->upload)){
            if($sname = $request->upload->store('images')){
                return response(["status" => "server", "sname" => $sname]);
            }else{
                return response(["status" => "error"], 500);
            }
        }
        $request->permissions = array_diff(explode(',', $request->permissions), array(''));
        $request->roles = array_diff(explode(',', $request->roles), array(''));
        $request->password = bcrypt($request->password);
        $admin = Administrator::create($request->all());
        if(count($request->permissions)) $admin->permissions()->attach($request->permissions);
        if(count($request->roles)) $admin->roles()->attach($request->roles);
        return response($admin, 201);
    }

    public function update(Request $request)
    {
        $req = $request->all();
        $req['permissions'] = array_diff(explode(',', $req['permissions']), array(''));
        $req['roles'] = array_diff(explode(',', $req['roles']), array(''));
        if(!empty($req['password'])) $req['password'] = bcrypt($req['password']); else unset($req['password']);
        $admin = Administrator::updateOrCreate(['id' => $request->id], $req);
        $admin->permissions()->sync($req['permissions']);
        $admin->roles()->sync($req['roles']);
        return response($admin, 202);
    }

    public function delete(Request $request)
    {
        if(!is_null($request->ids) && is_array($request->ids) && count($request->ids)){
            $admin = Administrator::whereIn('id', $request->ids)->delete();
            return response(['status' => 'deleted']);
        }
    }

}