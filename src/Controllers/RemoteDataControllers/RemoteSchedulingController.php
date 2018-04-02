<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Addons\Scheduling\Scheduling;

class RemoteSchedulingController extends Controller{

    public function get(Request $request)
    {

        $scheduling = new Scheduling();
        $data = []; $i=1;
        foreach ($scheduling->getTasks() as $task){
            $task['id'] = $i;
            $data[] = $task;
            $i++;
        }

        return response($data, 200);
    }

    public function insert(Request $request)
    {
        $scheduling = new Scheduling();

        try {
            $output = $scheduling->runTask($request->id);

            return [
                'status'    => true,
                'message'   => 'success',
                'data'      => $output,
            ];
        } catch (\Exception $e) {
            return [
                'status'    => false,
                'message'   => 'failed',
                'data'      => $e->getMessage(),
            ];
        }
    }

}