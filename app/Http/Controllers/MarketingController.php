<?php

namespace App\Http\Controllers;

class MarketingController extends Controller
{
    public function index()
    {
        return view('marketing.landing');
    }
}
