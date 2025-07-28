<?php

namespace App\Http\Controllers;

use App\Models\PensionEmployee;
use App\Models\MasterServiceDetails;
use App\Models\EmpFormSubmissionStatus;
use App\Models\EmpDocsPhotos;
use Illuminate\Support\Facades\Crypt;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return "dashboard";
    }
}
