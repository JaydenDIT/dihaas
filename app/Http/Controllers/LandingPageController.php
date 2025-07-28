<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationModel;

class LandingPageController extends Controller
{
    public function dihas_overview(){

        return view('admin/Form/dihas_overview');
    }

    public function sitemap(){

        return view('admin/Form/sitemap');
    }

   public function department_login(){

    $notifications = NotificationModel::orderBy("created_at")->paginate(1);
    return view('admin/Form/department_login', compact('notifications'));
   }
   public function contact_us(){

    return view('admin/Form/contact_us');
}

}