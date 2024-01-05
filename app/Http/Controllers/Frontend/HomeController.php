<?php

namespace App\Http\Controllers\Frontend;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        // // Check if the user is logged in
        // if (auth()->check()) {
        //     // If logged in, redirect to the admin dashboard
        //     return redirect()->route('admin.dashboard');
        // }

        return view('frontend.index');
    }

    public function constituencyWise()
    {
        return view('frontend.constituency-wise');
    }
}
