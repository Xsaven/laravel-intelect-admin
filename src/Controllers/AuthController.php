<?php

namespace Lia\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Lia\Facades\Admin;

class AuthController extends Controller
{
    /**
     * Login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if (!Auth::guard('admin')->guest()) {
            return redirect(config('lia.route.prefix'));
        }

        Admin::css(asset('vendor/lia/css/webix/contrast.css'));
        Admin::css(asset('vendor/lia/css/bootstrap/css/bootstrap.min.css'));
        Admin::css(asset('vendor/lia/css/layout.css'));
        Admin::css(asset('vendor/lia/css/AdminLTE.min.css'));
        Admin::css(asset('vendor/lia/css/goldenlayout/goldenlayout-base.css'));
        Admin::css(asset('vendor/lia/css/goldenlayout/goldenlayout-dark-theme.css'));
        Admin::js('http://cdn.webix.com/edge/webix.js');

        return view('lia::login');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        $validator = Validator::make($credentials, [
            'username' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if (Auth::guard('admin')->attempt($credentials)) {
            session(['cd_base_path' => base_path()]);
            return response(['redirect' => config('lia.route.prefix')], 200);
        }

        return response(['username' => $this->getFailedLoginMessage()], 401);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout()
    {
        Auth::guard('admin')->logout();

        session()->forget('url.intented');

        return redirect(config('lia.route.prefix'));
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }
}
