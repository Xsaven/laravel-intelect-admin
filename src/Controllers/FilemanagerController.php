<?php

namespace Lia\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Help\Filemanager\PHPFileSystem;

class FilemanagerController extends Controller
{
    private $api = false;

    public function __construct()
    {
        $this->api = new PHPFileSystem(base_path());
    }

    public function index()
    {
        $this->api->virtualRoot("Files");
        return response($this->api->ls("/", false, "branch"));
    }

    public function branch(Request $request)
    {
        return response($this->api->ls($request->source,false,"branch"));
    }

    public function search(Request $request)
    {
        return response($this->api->find($request->source,$request->text));
    }

    public function upload(Request $request)
    {
        $result = $this->api->upload(
            $request->target,
            $_FILES['upload']['name'],
            $_FILES['upload']['tmp_name']);

        return response($result);
    }

    public function download(Request $request)
    {
        $info = $this->api->download($request->source);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$info->getName().'"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $info->getSize());
        echo $info->getContent();
    }

    public function copy(Request $request)
    {
        return response($this->api->batch($request->source, array($this->api, "cp"), $request->target));
    }

    public function move(Request $request)
    {
        return response($this->api->batch($request->source, array($this->api, "mv"), $request->target));
    }

    public function remove(Request $request)
    {
        return response($this->api->batch($request->source, array($this->api, "rm")));
    }

    public function rename(Request $request)
    {
        return response($this->api->rename($request->source, $request->target));
    }

    public function create(Request $request)
    {
        return response($this->api->mkdir($request->source, $request->target));
    }
}
