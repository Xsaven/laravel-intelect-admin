<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Auth\Database\Menu;
use Lia\Auth\Database\Role;

class RemoteMenuController extends Controller{

    public function get(Request $request)
    {
        if(!is_null($request->fromTop)) {
            $menu = \Help\ArrayClass::clone(Menu::orderby('order', 'asc')->get(),['title' => 'value', 'order' => '$index']);
            foreach ($menu as $key => $val) $menu[$key]['icon'] = str_replace('fa-', '', $menu[$key]['icon']);
            $menu = \Help\ArrayClass::array_tree($menu, 'parent_id', 'submenu');
            return response($menu);
        }

        if(!is_null($request->noTree)) {
            $menu = \Help\ArrayClass::clone(Menu::orderby('order', 'asc')->get(),['title' => 'value']);
            foreach ($menu as $key => $val) $menu[$key]['icon'] = str_replace('fa-', '', $menu[$key]['icon']);
            return array_merge([['id' => 0, 'value' => '(Root category)', 'icon' => 'circle']], $menu);
        }

        if(is_null($request->id)){
            $menu = \Help\ArrayClass::clone(Menu::orderby('order', 'asc')->get(),['title' => 'value', 'order' => '$index']);
            $menu = \Help\ArrayClass::array_tree($menu);
            return response($menu);
        }
        else{
            $menu = Menu::with('roles')->where('id', $request->id)->get()->toArray()[0];
            $menu['roles'] = implode(',',\Help\ArrayClass::convert($menu['roles'],'id','id'));
            return response($menu);
        }
    }

    public function insert(Request $request)
    {
        $req = $request->all();
        $req['parent_id'] = Menu::find($req['parent_id']) ? $req['parent_id'] : 0;
        $req['roles'] = array_diff(explode(',', $req['roles']), array(''));
        $menu = Menu::create($req);
        if(count($req['roles'])) $menu->roles()->attach($req['roles']);
        return response($menu, 201);
    }

    public function update(Request $request){
        if(!is_null($request->id)){

            if(!is_null($request->noRole)){

                //$menu = Menu::where('parent_id', $request->parent_id)->where('order', '>=', $request->order)->orderby('order','asc')->get();
                $menu = Menu::where('parent_id', $request->parent_id)->where('id', '!=', $request->id)->orderby('order','asc')->get();
                $order = [];
                $order[$request->order] = $request->id;
                $iteration = 0;
                foreach ($menu as $m){
                    if(!isset($order[$iteration])){
                        $order[$iteration] = $m->id;
                    }else{
                        $iteration++;
                        $order[$iteration] = $m->id;
                    }
                    $iteration++;
                }

                //return response($order);

                foreach ($order as $orderNo => $id){
                    $updMenu = Menu::find($id);
                    $updMenu->order = $orderNo;
                    $updMenu->parent_id = $request->parent_id;
                    $updMenu->save();
                }

                return response(['status' => 'success'],200);
            }else{
                $req = $request->all();
                $req['parent_id'] = Menu::find($req['parent_id']) ? $req['parent_id'] : 0;
                $req['roles'] = array_diff(explode(',', $req['roles']), array(''));
                $menu = Menu::updateOrCreate(['id' => $request->id], $req);
                $menu->roles()->sync($req['roles']);
                return response($menu, 202);
            }
        }
    }

    public function delete(Request $request)
    {
        if(!is_null($request->id)){
            $menu = Menu::find($request->id);
            $menu->roles()->sync([]);
            $menu->delete();
            return response(['status' => 'Deleted!'], 200);
        }
    }

}