<?php

namespace App\Http\Controllers;

use App\Services\Account\AgencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\UserService;
use App\Services\Account\AgentService;
use App\Services\Account\DecoderService;
use App\Services\Account\OrganizationService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Auth::user()->role=='agent'){

            return AgentService::index();
        }
        elseif(Auth::user()->role=='organization'){
            
            return AgencyService::index();
        }
        elseif(Auth::user()->role=='decoder'){
            return DecoderService::index();
        }
        return UserService::index();
    }
}
