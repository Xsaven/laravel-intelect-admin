<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lia\Addons\GoogleTranslate\TranslateClient;

class RemoteTranslateController extends Controller{

    public function get(Request $request)
    {
        if(is_null($request->value)) return '';
        if(is_null($request->to_lang)) return '';
        $tr = new TranslateClient();
        return $tr->setTarget($request->to_lang)->translate($request->value);
    }
}