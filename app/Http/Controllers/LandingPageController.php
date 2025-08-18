<?php
namespace App\Http\Controllers;

class LandingPageController extends Controller
{
    public function dihas_overview()
    {
        return view('admin/Form/dihas_overview');
    }

    public function sitemap()
    {
        return view('admin/Form/sitemap');
    }

    public function contact_us()
    {
        return view('admin/Form/contact_us');
    }
}
