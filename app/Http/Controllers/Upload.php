<?php

namespace App\Http\Controllers;

use App\Models\Docs;
use App\Models\EmpFormSubmissionStatus;
use App\Models\FileModel;
use App\Models\PensionEmployee;
use App\Models\ProformaModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Library\Senitizer;

class Upload extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        if(isset($_REQUEST)){
            $_REQUEST = Senitizer::senitize($_REQUEST);
       }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // this function is use to see the EIN EMPname dob and dos aaaaaaaaaaaaaaaa
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        if (session()->has('ein')) {
            $ein = session()->get('ein');


            //return $ein;

            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
             //Preferences add for status 1 or 2 or 3
             $Expire_Duty = $emp_form_stat->expire_on_duty;
             $physic =  $emp_form_stat->physically_handicapped;
             $uploader=  $emp_form_stat->uploader_role_id;
             
            // dd($Expire_Duty, $physic, $uploader);
            
            if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
               // dd($uploader,$Expire_Duty,$physic);
                $status = [2, 5];               
               $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [3, 2, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [3, 2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
                $status = [1, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [1, 3, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [1, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [1, 3, 4, 5];
                $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            }         
                    
                   
          

            ////////////////////////////////////////////////////////////////////////////////////
            $empDetails = $emp_form_stat;

            if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            // check personal details submit or not
            $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->where('form_id', 1)->toArray();

            if (count($emp_form_stat) == 0) {
                return redirect()->route('viewPersonalDetailsFrom', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Descriptive Role First!");
            }
            // end....................
            // view family Details form

            // $emp_family_details = FamilyMembers::orderBy('id', 'ASC')->where("ein", $ein)->get();



            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
             //Preferences add for status 1 or 2 or 3
             $Expire_Duty1 = $empDetails->expire_on_duty;
             $physic1 =  $empDetails->physically_handicapped;
             $uploader1=  $empDetails->uploader_role_id;
             
            // dd($Expire_Duty, $physic, $uploader);
            if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
               // dd($uploader,$Expire_Duty,$physic);
                $status = [2, 5];               
               $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [3, 2, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [3, 2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
                $status = [1, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [1, 3, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [1, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [1, 3, 4, 5];
                $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            }         
                            

            $files = FileModel::get()->where('ein', $ein)->toArray();
            //dd($files);
            $fileArray = array();
            foreach ($files as $file) {
                $filename = explode('/', $file['file_path']);
                $fileArray[$file['doc_id']] = $filename[2];
            }


            return view('admin/Form/uploaded', compact("fileArray", "reqDocs", "formStatArray", "empDetails", "ein", "status"));
        } else {
            return redirect()->route('viewStartEmp');
        }
    }




    public function createForm()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //return 2;
        $ein = null;
        $empDetails = null;
        // dd(session());
        if (session()->has('ein')) {
            $ein = session()->get('ein');
           
            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
           
            //Preferences add for status 1 or 2 or 3
            $Expire_Duty = $empDetails->expire_on_duty;
            $physic =  $empDetails->physically_handicapped;
            $uploader=  $empDetails->uploader_role_id;
            
           // dd($Expire_Duty, $physic, $uploader);
           if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
           // dd($uploader,$Expire_Duty,$physic);
            $status = [2, 5];               
           $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [3, 2, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [3, 2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
            $status = [1, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [1, 3, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [1, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [1, 3, 4, 5];
            $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        }         
                
           
           // $reqDocs = Docs::orderBy('doc_id')->get()->where('status', 1)->toArray();
            //dd($reqDocs);
            return view('admin/Form/upload', compact('ein', 'reqDocs', 'empDetails'));
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/upload', compact('ein', 'reqDocs', 'empDetails'));
    }


   

    /**
     * Download file
     * @param filename
     */
    // public function download($doc_id)
    // {

    //     $fileID=$doc_id;
    //     $ein = null;
    //     $empDetails = null;
    //     if (session()->has('ein')) {
    //         $ein = session()->get('ein');

    //     }

    //         //get file path from db using $ein and $doc_id
    //        // $emp = ProformaModel::get()->where("ein", $ein)->first();
    //        //dd($fileID);
    //         $fileDocs = FileModel::get()->where("doc_id", $fileID)->where("ein", $ein)->toArray(); //orderBy place before get
    //        // dd($fileDocs);


    //         foreach ($fileDocs as $data) {
    //             if ($data != null) {

    //                 // $pre = $data['ein'];
    //                 // $suffix = "_".$data['doc_id'];

    //                 // $dbfilepath = "uploads/$pre$suffix";

    //                 //return  $dbfilepath;

    //                // $dbfilepath = explode('/', $dbfilepath); //explode make dbfilepath into 2 part. uploads as 0 and $pre.$suffix as 1
    //                // $url = storage_path() . '\\app\\uploads\\' . $ein . '\\' . $dbfilepath[1];
    //                // dd(storage_path());
    //                //dd($data['file_path']);

    //                 // $url = Storage::path($data['file_path']);
    //                 // $url = Storage::disk('disk_name')->get($data['file_name']);
    //                 $defaultUrl = config('filesystems.disks');
    //                 // $url = storage_path() . '\\app\\'. $data['file_path'];
    //                 $url =  $defaultUrl['public']['url'].'/app/'.$data['file_path'];

    //                 if (!file_exists($url)) {

    //                     //redirect to error
    //                     return redirect()->back()->with('message', ' File not Found!!!!');
    //                 }
    //                 return response()->file($url);

    //             } else {
    //                 return redirect()->back()->with('message', ' No uploaded file Found!!!!');
    //             }
    //         }

    // }

    //VIEW FILE WHICH ARE ALREADY UPLOADED

    public function viewFile($doc_id)
    {
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //get file path from DB using $ein and $doc_id

            $fileDocs = FileModel::where('ein', $ein)->where('doc_id', $doc_id)->first();

            if (!$fileDocs) {
                // File not found, redirect to error page or show error message
                return redirect()->back()->with('errormessage', ' File not Found!!!!');
            } else {
                $headers = ['Content-Type: application/pdf'];
                $pre = $fileDocs['ein'] ?? '';
                $suffix = "_" . $fileDocs['doc_id'] ?? '';

                $dbfilepath = "uploads" . DIRECTORY_SEPARATOR . "{$pre}" . DIRECTORY_SEPARATOR . "{$pre}{$suffix}.pdf";


                $url = storage_path("app" . DIRECTORY_SEPARATOR . $dbfilepath);
                //return $url;
                return response()->file($url, $headers);
            }
        }
    }
    // DOWNLOAD FILE

    public function downloadFile($doc_id)
    {

        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //get file path from DB using $ein and $doc_id

            $fileDocs = FileModel::where('ein', $ein)->where('doc_id', $doc_id)->first();

            if (!$fileDocs) {
                // File not found, redirect to error page or show error message
                return redirect()->back()->with('errormessage', ' File not Found!!!!');
            } else {

                $headers = ['Content-Type: application/pdf'];
                $pre = $fileDocs['ein'] ?? '';
                $suffix = "_" . $fileDocs['doc_id'] ?? '';
                $dbfilepath = "uploads" . DIRECTORY_SEPARATOR . "{$pre}" . DIRECTORY_SEPARATOR . "{$pre}{$suffix}.pdf";
                $myFile = storage_path("app" . DIRECTORY_SEPARATOR . $dbfilepath);
                $newName = "{$pre}{$suffix}" . time() . '.pdf';


                return response()->download($myFile, $newName, $headers);
            }
        }
    }




    public function createFormbacklog()
    {
        $ein = null;
        $empDetails = null;
        // dd(session());
        if (session()->has('ein')) {
            $ein = session()->get('ein');
            //  dd($ein);

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
           
            //Preferences add for status 1 or 2 or 3
            $Expire_Duty = $empDetails->expire_on_duty;
            $physic =  $empDetails->physically_handicapped;
            $uploader=  $empDetails->uploader_role_id;
            
           // dd($Expire_Duty, $physic, $uploader);
           
           if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
          //  dd($uploader,$Expire_Duty,$physic);
            $status = [2, 5];               
           $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [3, 2, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [3, 2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
            $status = [1, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [1, 3, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [1, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [1, 3, 4, 5];
            $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        }         
                  
                    
            //dd($reqDocs);
            return view('admin/Form/upload_backlog', compact('ein', 'reqDocs', 'empDetails'));
        }
       else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/upload_backlog', compact('ein', 'reqDocs', 'empDetails'));
    }

       

    public function fileUploadbacklog(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $req->validate([
            'file' => 'mimes:jpg,jpeg,pdf|max:2048' //remove required :: this line was the problem
        ]);

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            foreach ($req->row as $docs) {
                $fileModel = new FileModel();
                if (array_key_exists('file', $docs)) {

                    $file = file_get_contents($docs['file']); //geting file 

                    $newfilename = $ein . "_" . $docs['doc_id'] . "." . $docs['file']->getClientOriginalExtension();
                    $filePath = $docs['file']->storeAs('uploads/' . $ein, $newfilename);


                    $fileModel->ein = $ein;
                    $fileModel->appl_number = $appl_number;
                    $fileModel->uploaded_by = $uploaded_by;
                    $fileModel->doc_id = $docs['doc_id'];
                    $fileModel->file_name = $docs['file']->getClientOriginalName(); //save original file name
                    //$fileModel->file_name = $docs['doc_name'];//save document name in file name
                    $fileModel->file_path = $filePath; //add file path after saving the file
                    $fileModel->status = 1;
                    $fileModel->save();
                }
            }
        }
       // return redirect()->route('other_form_details_submit_backlog')->with('status', 'Files have been uploaded successfully....');
        return back();//->with('status', 'Files have been uploaded successfully....');
    }

    public function deleteFileUploadbacklog(Request $req){

        $doc_id = $req->input('deleteId');
        $ein =  session()->get('ein');

        FileModel::where('ein', $ein)->where('doc_id',$doc_id)->delete();

        return back()->with('status', 'File has been removed');
    }


    /** Final submit file upload */
    public function fileUploadSubmitbacklog(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();      

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            $tempArray = [];
            $error_docs = [];
          
 
            $uploadeddocsarray = array();

            $fileModel = FileModel::get()->where('ein',$ein);
            foreach($fileModel as $file)
                array_push($uploadeddocsarray,$file->doc_id);
            foreach ($req->row as $docs) {
                   if ($docs['is_mandatory'] == 'Y' && !in_array($docs['doc_id'],$uploadeddocsarray)) {
                        return back()
                        ->with('errormessage', 'Mandatory files are missing...');
                 }

            }
 
              
             $clientIP = $req->ip();
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'upload_status' => 1,
                    'client_ip' => $clientIP,
                ]);
            }
            // insert to form submission status 
            $formId = 3; // here we set upload details form id as 3 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                // $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                // $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                // $row->submit_status = 1;
                // $row->save();
               // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
                return back()->with('errormessage', 'Already Submitted....');
                // return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
        }
       return back()->with('status', 'Files have been submitted successfully....');
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////


    public function createFormUpdate(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            //  dd($ein);

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();

           //Preferences add for status 1 or 2 or 3
           $Expire_Duty = $empDetails->expire_on_duty;
           $physic =  $empDetails->physically_handicapped;
           $uploader=  $empDetails->uploader_role_id;
           
          // dd($Expire_Duty, $physic, $uploader);
          
          if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
           // dd($uploader,$Expire_Duty,$physic);
            $status = [2, 5];               
           $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [3, 2, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [3, 2, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
            $status = [1, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
            $status = [1, 3, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
        } 

        if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
            $status = [1, 4, 5];
          $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        } 
        if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
            $status = [1, 3, 4, 5];
            $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
        }                      
                   


            $files = FileModel::get()->where('ein', $ein)->toArray();
            //dd($files);
            $fileArray = array();
            foreach ($files as $file) {
                $filename = explode('/', $file['file_path']);
                $fileArray[$file['doc_id']] = $filename[2];
            }
            //dd($fileArray);
            return view('admin/Form/uploadUpdate', compact('reqDocs', 'empDetails', 'fileArray'));
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////////////////////////////////////////////////////////
    public function fileUpload(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $req->validate([
            'file' => 'mimes:jpg,jpeg,pdf|max:2048' //remove required :: this line was the problem
        ]);

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            foreach ($req->row as $docs) {
                $fileModel = new FileModel();
                if (array_key_exists('file', $docs)) {

                    $file = file_get_contents($docs['file']); //geting file 

                    $newfilename = $ein . "_" . $docs['doc_id'] . "." . $docs['file']->getClientOriginalExtension();
                    $filePath = $docs['file']->storeAs('uploads/' . $ein, $newfilename);


                    $fileModel->ein = $ein;
                    $fileModel->appl_number = $appl_number;
                    $fileModel->uploaded_by = $uploaded_by;
                    $fileModel->doc_id = $docs['doc_id'];
                    $fileModel->file_name = $docs['file']->getClientOriginalName(); //save original file name
                    //$fileModel->file_name = $docs['doc_name'];//save document name in file name
                    $fileModel->file_path = $filePath; //add file path after saving the file
                    $fileModel->status = 1;
                    $fileModel->save();
                }
            }
        }
         return back();//->with('status', 'File have been uploaded successfully....');
    }

    public function deleteFileUpload(Request $req){

        $doc_id = $req->input('deleteId');
        $ein =  session()->get('ein');

        FileModel::where('ein', $ein)->where('doc_id',$doc_id)->delete();

        return back()->with('status', 'File has been removed');
    }


    /** Final submit file upload */
    public function fileUploadSubmit(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();      

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            $tempArray = [];
            $error_docs = [];
          
 
            $uploadeddocsarray = array();

            $fileModel = FileModel::get()->where('ein',$ein);
            foreach($fileModel as $file)
                array_push($uploadeddocsarray,$file->doc_id);
            foreach ($req->row as $docs) {
                   if ($docs['is_mandatory'] == 'Y' && !in_array($docs['doc_id'],$uploadeddocsarray)) {
                        return back()
                        ->with('errormessage', 'Mandatory files are missing...');
                 }

            }
 
              
             $clientIP = $req->ip();
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'upload_status' => 1,
                    'client_ip' => $clientIP,
                ]);
            }
            // insert to form submission status 
            $formId = 3; // here we set upload details form id as 3 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                // $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                // $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                // $row->submit_status = 1;
                // $row->save();
               // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
                return back()->with('errormessage', 'Already Submitted....');
                // return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
        }
       return back()->with('status', 'Files have been submitted successfully....');
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////WORKING CODE///////////////////////////////////////////////////////////////////
    public function fileUploadUpdate(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $req->validate([
            'file' => 'mimes:jpg,jpeg,pdf|max:2048' //remove required :: this line was the problem
        ]);

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            foreach ($req->row as $docs) {
                $fileModel = new FileModel();
                if (array_key_exists('file', $docs)) {

                    $file = file_get_contents($docs['file']); //geting file 

                    $newfilename = $ein . "_" . $docs['doc_id'] . "." . $docs['file']->getClientOriginalExtension();
                    $filePath = $docs['file']->storeAs('uploads/' . $ein, $newfilename);


                    $fileModel->ein = $ein;
                    $fileModel->appl_number = $appl_number;
                    $fileModel->uploaded_by = $uploaded_by;
                    $fileModel->doc_id = $docs['doc_id'];
                    $fileModel->file_name = $docs['file']->getClientOriginalName(); //save original file name
                    //$fileModel->file_name = $docs['doc_name'];//save document name in file name
                    $fileModel->file_path = $filePath; //add file path after saving the file
                    $fileModel->status = 1;
                    $fileModel->save();
                }
            }
        }
       //  return back();//->with('status', 'File have been uploaded successfully....');
    return response()->json(['status' => 'success']);
    }

 
    public function deleteFileUploadSubmit(Request $req){

        $doc_id = $req->input('docId');
        $ein =  session()->get('ein');

        FileModel::where('ein', $ein)->where('doc_id',$doc_id)->delete();
//echo ($doc_id);
        //return back()->with('status', 'File has been removed');
    return response()->json(['status' => 'success', 'message' => 'File has been removed']);
    }


    /** Final submit file upload */
    public function finalFileUploadSubmit(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();      

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            $tempArray = [];
            $error_docs = [];
          
 
            $uploadeddocsarray = array();

            $fileModel = FileModel::get()->where('ein',$ein);
            foreach($fileModel as $file)
                array_push($uploadeddocsarray,$file->doc_id);
            foreach ($req->row as $docs) {
                   if ($docs['is_mandatory'] == 'Y' && !in_array($docs['doc_id'],$uploadeddocsarray)) {
                        return response()->json(['status'=> 'error', 'message'=> 'Mandatory files are missing...']);
                 }

            }
 
              
             $clientIP = $req->ip();
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'upload_status' => 1,
                    'client_ip' => $clientIP,
                ]);
            }
            // insert to form submission status 
            $formId = 3; // here we set upload details form id as 3 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                $row->submit_status = 1;
                $row->save();
              
                //return back()->with('status', 'Files have been uploaded successfully....');
               // return response()->json(['message' => 'Files have been uploaded successfully....']);
    if ($req->ajax()) {
        return response()->json(['status' => 'success', 'message' => 'Files have been submitted successfully']);
    }
               
            }
        }
        //return response()->json(['message' => 'Files have been uploaded successfully....']);
       //return back()->with('status', 'Files have been submitted successfully....');
    if ($req->ajax()) {
        return response()->json(['status' => 'success', 'message' => 'Files have been submitted successfully']);
    }
    }
    ///////////////////////////END CODE////////////////////////////////////////////////////////////////////
   
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function createApplicantUpdate()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            //  dd($ein);

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            //Preferences add for status 1 or 2 or 3
            $Expire_Duty = $empDetails->expire_on_duty;
            $physic =  $empDetails->physically_handicapped;
            $uploader=  $empDetails->uploader_role_id;
            
           // dd($Expire_Duty, $physic, $uploader);
           
            if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
             //   dd($uploader,$Expire_Duty,$physic);
                $status = [2, 5];               
               $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
               // dd($uploader,$Expire_Duty,$physic);
                $status = [3, 2, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [3, 2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
                $status = [1, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [1, 3, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [1, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [1, 3, 4, 5];
                $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            }         
                    

            $files = FileModel::get()->where('ein', $ein)->toArray();
            //dd($files);
            $fileArray = array();
            foreach ($files as $file) {
                $filename = explode('/', $file['file_path']);
                $fileArray[$file['doc_id']] = $filename[2];
            }
            //dd($fileArray);
            return view('admin/Form/uploadApplicantUpdate', compact('reqDocs', 'empDetails', 'fileArray'));
        }
    }


    public function fileApplicantUpdate(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $req->validate([
            'file' => 'mimes:jpg,jpeg,pdf|max:2048' //remove required :: this line was the problem
        ]);

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            foreach ($req->row as $docs) {
                $fileModel = new FileModel();
                if (array_key_exists('file', $docs)) {

                    $file = file_get_contents($docs['file']); //geting file 

                    $newfilename = $ein . "_" . $docs['doc_id'] . "." . $docs['file']->getClientOriginalExtension();
                    $filePath = $docs['file']->storeAs('uploads/' . $ein, $newfilename);


                    $fileModel->ein = $ein;
                    $fileModel->appl_number = $appl_number;
                    $fileModel->uploaded_by = $uploaded_by;
                    $fileModel->doc_id = $docs['doc_id'];
                    $fileModel->file_name = $docs['file']->getClientOriginalName(); //save original file name
                    //$fileModel->file_name = $docs['doc_name'];//save document name in file name
                    $fileModel->file_path = $filePath; //add file path after saving the file
                    $fileModel->status = 1;
                    $fileModel->save();
                }
            }
        }
         return back();//->with('status', 'File have been uploaded successfully....');
    }

    public function deleteFileUploadSubmitself(Request $req){
       // dd($req->input('deleteId'));
        
        $doc_id = $req->input('deleteId');
        $ein =  session()->get('ein');

        FileModel::where('ein', $ein)->where('doc_id',$doc_id)->delete();

        return back()->with('status', 'File has been removed');
    }


    /** Final submit file upload */
    public function finalFileUploadSubmitself(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();      

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            $tempArray = [];
            $error_docs = [];
          

            $uploadeddocsarray = array();

            $fileModel = FileModel::get()->where('ein',$ein);
            foreach($fileModel as $file)
                array_push($uploadeddocsarray,$file->doc_id);
            foreach ($req->row as $docs) {
                   if ($docs['is_mandatory'] == 'Y' && !in_array($docs['doc_id'],$uploadeddocsarray)) {
                        return back()
                        ->with('errormessage', 'Mandatory files are missing...');
                 }

            }
            $clientIP = $req->ip();
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'upload_status' => 1,
                    'client_ip' => $clientIP,
                ]);
            }
            // insert to form submission status 
            $formId = 3; // here we set upload details form id as 3 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                $row->submit_status = 1;
                $row->save();
               // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
                return back()->with('status', 'Files have been uploaded successfully....');
                // return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
        }
        // return $tempArray; // file not uploaded
       // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
        return back()->with('status', 'Files have been uploaded successfully....');
    }

    public function createForm2ndAppl()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //return 2;
        $ein = null;
        $empDetails = null;
        // dd(session());
        if (session()->has('ein')) {
            $ein = session()->get('ein');
            //  dd($ein);

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
             //Preferences add for status 1 or 2 or 3
             $Expire_Duty = $empDetails->expire_on_duty;
             $physic =  $empDetails->physically_handicapped;
             $uploader=  $empDetails->uploader_role_id;
             
            // dd($Expire_Duty, $physic, $uploader);
            
            if ($uploader == 77 && $Expire_Duty == 'no' && $physic == 'no') {
           //     dd($uploader,$Expire_Duty,$physic);
                $status = [2, 5];               
               $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [3, 2, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 77  && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [3, 2, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'no') {
                $status = [1, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'no') {
                $status = [1, 3, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status', $status)->toArray(); //orderBy place before get  
            } 
 
            if ($uploader == 1 && $Expire_Duty == 'no' && $physic == 'yes') {
                $status = [1, 4, 5];
              $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            } 
            if ($uploader == 1 && $Expire_Duty == 'yes' && $physic == 'yes') {
                $status = [1, 3, 4, 5];
                $reqDocs = Docs::orderBy('doc_id')->get()->whereIn('status',  $status)->toArray(); //orderBy place before get  
            }         
            $files = FileModel::get()->where('ein', $ein)->toArray();
            //dd($files);
            $fileArray = array();
            foreach ($files as $file) {
                $filename = explode('/', $file['file_path']);
                $fileArray[$file['doc_id']] = $filename[2];
            }     

            return view('admin/Form/upload2ndAppl', compact('fileArray','reqDocs', 'empDetails'));
        }
    }


    public function fileUpload2ndAppl(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $req->validate([
            'file' => 'mimes:jpg,jpeg,pdf|max:2048' //remove required :: this line was the problem
        ]);

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            foreach ($req->row as $docs) {
                $fileModel = new FileModel();
                if (array_key_exists('file', $docs)) {

                    $file = file_get_contents($docs['file']); //geting file 

                    $newfilename = $ein . "_" . $docs['doc_id'] . "." . $docs['file']->getClientOriginalExtension();
                    $filePath = $docs['file']->storeAs('uploads/' . $ein, $newfilename);


                    $fileModel->ein = $ein;
                    $fileModel->appl_number = $appl_number;
                    $fileModel->uploaded_by = $uploaded_by;
                    $fileModel->doc_id = $docs['doc_id'];
                    $fileModel->file_name = $docs['file']->getClientOriginalName(); //save original file name
                    //$fileModel->file_name = $docs['doc_name'];//save document name in file name
                    $fileModel->file_path = $filePath; //add file path after saving the file
                    $fileModel->status = 1;
                    $fileModel->save();
                }
            }
        }
         return back();//->with('status', 'File have been uploaded successfully....');
    }

    public function deleteFileUpload2ndAppl(Request $req){
       // dd($req->input('deleteId'));
        
        $doc_id = $req->input('deleteId');
        $ein =  session()->get('ein');

        FileModel::where('ein', $ein)->where('doc_id',$doc_id)->delete();

        return back()->with('status', 'File has been removed');
    }


    /** Final submit file upload */
    public function fileUploadSubmit2ndAppl(Request $req)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();      

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            $appl_number = $empDetails['appl_number'];
            $uploaded_by = $getUser->id;

            $tempArray = [];
            $error_docs = [];
          

            $uploadeddocsarray = array();

            $fileModel = FileModel::get()->where('ein',$ein);
            foreach($fileModel as $file)
                array_push($uploadeddocsarray,$file->doc_id);
            foreach ($req->row as $docs) {
                   if ($docs['is_mandatory'] == 'Y' && !in_array($docs['doc_id'],$uploadeddocsarray)) {
                        return back()
                        ->with('errormessage', 'Mandatory files are missing...');
                 }

            }
            $clientIP = $req->ip();
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'upload_status' => 1,
                    'client_ip' => $clientIP,
                ]);
            }
            // insert to form submission status 
            $formId = 3; // here we set upload details form id as 3 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                $row->submit_status = 1;
                $row->save();
               // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
                return back()->with('status', 'Files have been uploaded successfully....');
                // return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
        }
        // return $tempArray; // file not uploaded
       // return redirect()->route('other_form_details_dihas')->with('status', 'Updated Files have been uploaded successfully....');
        return back()->with('status', 'Files have been uploaded successfully....');
    }


}
