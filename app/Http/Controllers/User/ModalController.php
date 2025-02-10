<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function setupBill()
    {
        try {
            $data['dashboard_title'] = "Wallet";
            return view('user.modals.setupBill', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function wallet()
    {
        try {
            $data['dashboard_title'] = "Wallet";
            return view('user.modals.wallet', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
}
