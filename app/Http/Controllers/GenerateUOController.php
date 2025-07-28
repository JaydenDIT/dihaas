<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\ProformaModel;
use App\Models\RemarksModel;
use App\Models\Sign1Model;
use App\Models\Sign2Model;
use App\Models\UOGenerationModel;
use App\Models\UoNomenclatureModel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
//use Barryvdh\DomPDF\PDF;
//use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
//use Illuminate\Http\Response;

class GenerateUOController extends Controller
{
    public function generatePDF($id)
    {
        
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //the above code needed for menu of header as per user
        // if(Session::get($getUser->role_id)!==null){
        // dd($id)    ;
        $id = Crypt::decryptString($id);

        $uoGeneration = UOGenerationModel::get()->where('ein', $id)->first();

        if (!$uoGeneration) {
            return back()->with('message', 'Please fill the UO form'); // Redirect back with an alert message
        }
        //     //id is encrypted and send through URL
        //    // Below ein is passed in the session
        if (session()->has('ein')) {
            $session_ein = session()->get('ein');
        }
        // set a session emp EIN 
        session()->put('ein', $id);
        $ein = session()->get('ein');       

        $uoGenerationLast =  UOGenerationModel::orderBy('id', 'DESC')->first();       

        //GETTING DATA FROM number_generator table
        $fix = UoNomenclatureModel::get()->first();
        //dd($fix);
        $prefixData = $fix->uo_format;
        $yearData = $fix->year;
        $suffixData = $fix->suffix;

        if ($uoGenerationLast != null) {
            $lastID = $uoGenerationLast->id;
            $nextNumber = $lastID + 1;

            $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
        } else {
            $lastID = 0;
            $nextNumber = $lastID + 1;
            $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
        }

        $getDate = Carbon::now()->format('Y-m-d');
      
    // save while pdf generation
    if($uoGeneration != null)
    $uoGeneration->update([     
        'uo_number' => $getUOFileNo,        
        'generated_by' => $getUser->name,
        'generated_on' => $getDate

    ]   );
    
    $empDetails = ProformaModel::get()->where("ein", $ein)->first();
    if ($empDetails != null) {
        $empDetails->update([
           'status' => 6, //Can view Order of Appointment
            'forwarded_on' =>  $getDate
                             
        ]);
    }    
    $data = ProformaModel::get()->where('ein', $ein)->where('status', 6)->first();
       
        $uoGenerated = UOGenerationModel::get()->where ('ein',$ein)->first();

        $Sign1 = Sign1Model::get()->where ('id',$uoGenerated->signing_authority_1)->first();
        $Sign2 = Sign2Model::get()->where ('id',$uoGenerated->signing_authority_2)->first();
 
        $html = view('admin.Form.generatePDFUO', [
            'data' => $data,
            // 'data2' => $data2,
             'post' => $uoGenerated->post,
             'grade' => $uoGenerated->grade,
             'department' => $uoGenerated->department,
             'relationship' => $uoGenerated->relationship,
             'adfilenumber' =>$uoGenerated->ad_file_number, // Pass the ad_file_number to the view
             'dpfilenumber' =>$uoGenerated->dp_file_number, // Pass the ad_file_number to the view
             'dpsign1' =>$Sign1->authority_name, 
             'adsign2' =>$Sign2->name,

             ])->render();

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ];

        $pdf = Pdf::loadHTML($html)->output();

        return Response::make($pdf, 200, $headers);
    }
    //     ])->render();
     
    //     $pdf = new Dompdf();
    //     $pdf->loadHtml($html, 'UTF-8'); // Customize the content of the PDF as per your needs
    //     //$pdf = PDF::loadView('myPDF', $html);

    //     $pdf->setPaper('A4', 'portrait'); // Set the paper size and orientation
    //     $pdf->render();

    //    //$headers= header('content-type: application/pdf');
       
    //     // Generate the PDF file and send it as a response
    //     return $pdf->stream('document.pdf', ['Attachment' => true]);   
      

  



    ///////////////////////////////////////////////////////////////////////////


    function pdfselected(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
    
    $selectedGrades = $request->input('selectedGrades', []);
    session()->put('selectedGrades', $selectedGrades);


    // $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
    // $Remarks = RemarksModel::get()->toArray();

    // $empDept = ProformaModel::get()->where('status', 6)->where('ein', $ein)->first();

    foreach ($selectedGrades as $grade) {
        $getDate = Carbon::now()->format('Y-m-d');

        $empDetails = ProformaModel::get()->where("ein", $grade)->first();
        if ($empDetails != null) {
            $empDetails->update([
               'status' => 6, //Can view Order of Appointment
                'forwarded_on' =>  $getDate
                                 
            ]);
        }

        $empList = ProformaModel::where('ein', $grade)->where('status', 6)->paginate(10);

       // dd($grade,$empDetails->dept_name);
       // $uoGeneration = UOGenerationModel::get()->where ('ein',$grade)->first();
        foreach ($empList as $data) {
           

            $uoGenerationLast =  UOGenerationModel::orderBy('id', 'DESC')->first();
                                        
               //GETTING DATA FROM number_generator table
                $fix = UoNomenclatureModel::get()->first();
                //dd($fix);
                $prefixData = $fix->uo_format;
                $yearData = $fix->year;
                $suffixData = $fix->suffix;

                if ($uoGenerationLast != null) {
                    $lastID = $uoGenerationLast->id;
                    $nextNumber = $lastID + 1;

                    //$getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                    $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                } else {
                    $lastID = 0;
                    $nextNumber = $lastID + 1;
                   // $getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                    $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                }

              
                $uoGeneration = UOGenerationModel::get()->where ('ein',$grade)->first();
                
                //dd($getUOFileNo);
                if($uoGeneration != null)
                    $uoGeneration->update([     
                        'uo_number' => $getUOFileNo,        
                        'generated_by' => $getUser->name,
                        'generated_on' => $getDate
                
                    ]   );

                   
            }
        }
        //$uoGeneration1 = UOGenerationModel::get()->where ('ein',$grade)->first();
        //dd($uoGeneration1);

        $uoGenerated = UOGenerationModel::get()->where ('ein',$grade)->first();
           //$signingAuthority = UOGenerationModel::get()->where ('ein',$grade)->first();
          
          $Sign1 = Sign1Model::get()->where ('id',$uoGenerated->signing_authority_1)->first();
        $Sign2 = Sign2Model::get()->where ('id',$uoGenerated->signing_authority_2)->first();
       // dd($Sign2->name);
 
            $html = view('admin.Form.generatePDFSelectedUO', [
    
               // 'signingAuthority' => $signingAuthority,
                'data' => $data,
                'post' => $uoGenerated->post,
                'grade' => $uoGenerated->grade,
                'department' => $uoGenerated->department,
                'relationship' => $uoGenerated->relationship,

                'adfilenumber' =>$uoGenerated->ad_file_number, // Pass the ad_file_number to the view
                'dpfilenumber' =>$uoGenerated->dp_file_number, // Pass the ad_file_number to the view
                'dpsign1' =>$Sign1->authority_name, 
                'adsign2' =>$Sign2->name,
            // ])->render();
    
            // $pdf = new Dompdf();
            // $pdf->loadHtml($html); // Customize the content of the PDF as per your needs
    
            // $pdf->setPaper('A4', 'portrait'); // Set the paper size and orientation
            // $pdf->render();
  
            // // Generate the PDF file and send it as a response
            // return $pdf->stream('document.pdf', ['Attachment' => true]);
            
            //  }
            ])->render();

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"',
            ];
    
            $pdf = Pdf::loadHTML($html)->output();
    
            return Response::make($pdf, 200, $headers);
        }
         
             ///////////////////////////////////////////////////////////////////////////
         
         
             function pdfselectedORDER(Request $request)
             {
                 $user_id = Auth::user()->id;
                 $getUser = User::get()->where('id', $user_id)->first();
             
             $selectedGrades = $request->input('selectedGrades', []);
             session()->put('selectedGrades', $selectedGrades);
         
             foreach ($selectedGrades as $grade) {
                 $empList = ProformaModel::where('ein', $grade)->where('status', 6)->paginate(10);
                // $uoGeneration = UOGenerationModel::get()->where ('ein',$grade)->first();
                 foreach ($empList as $data) {
                    
         
                     $uoGenerationLast =  UOGenerationModel::orderBy('id', 'DESC')->first();
                                                 
                        //GETTING DATA FROM number_generator table
                         $fix = UoNomenclatureModel::get()->first();
                         //dd($fix);
                         $prefixData = $fix->uo_format;
                         $yearData = $fix->year;
                         $suffixData = $fix->suffix;
         
                         if ($uoGenerationLast != null) {
                             $lastID = $uoGenerationLast->id;
                             $nextNumber = $lastID + 1;
         
                            // $getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                             $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                         } else {
                             $lastID = 0;
                             $nextNumber = $lastID + 1;
                            // $getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                             $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                         }
         
                         $getDate = Carbon::now()->format('Y-m-d');
                         $uoGeneration = UOGenerationModel::get()->where ('ein',$grade)->first();
                         
                         //dd($getUOFileNo);
                         if($uoGeneration != null)
                             $uoGeneration->update([     
                                 'uo_number' => $getUOFileNo,        
                                 'generated_by' => $getUser->name,
                                 'generated_on' => $getDate
                         
                             ]   );
         
                           
                     }
                 }
                 //$uoGeneration1 = UOGenerationModel::get()->where ('ein',$grade)->first();
                 //dd($uoGeneration1);
         
                 $uoGenerated = UOGenerationModel::get()->where ('ein',$grade)->first();
                    //$signingAuthority = UOGenerationModel::get()->where ('ein',$grade)->first();
                   
                   $Sign1 = Sign1Model::get()->where ('id',$uoGenerated->signing_authority_1)->first();
                 $Sign2 = Sign2Model::get()->where ('id',$uoGenerated->signing_authority_2)->first();
                // dd($Sign2->name);
          
                     $html = view('admin.Form.generatePDFSelectedUO', [
             
                        // 'signingAuthority' => $signingAuthority,
                         'data' => $data,
                         'post' => $uoGenerated->post,
                'grade' => $uoGenerated->grade,
                'department' => $uoGenerated->department,
                'relationship' => $uoGenerated->relationship,
                         'adfilenumber' =>$uoGenerated->ad_file_number, // Pass the ad_file_number to the view
                         'dpfilenumber' =>$uoGenerated->dp_file_number, // Pass the ad_file_number to the view
                         'dpsign1' =>$Sign1->authority_name, 
                         'adsign2' =>$Sign2->name,
                    //  ])->render();
             
                    //  $pdf = new Dompdf();
                    //  $pdf->loadHtml($html); // Customize the content of the PDF as per your needs
             
                    //  $pdf->setPaper('A4', 'portrait'); // Set the paper size and orientation
                    //  $pdf->render();
             
                    //  // Generate the PDF file and send it as a response
                    //  return $pdf->stream('document.pdf', ['Attachment' => true]);
                     
                    //   }
                    ])->render();

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="document.pdf"',
                    ];
            
                    $pdf = Pdf::loadHTML($html)->output();
            
                    return Response::make($pdf, 200, $headers);
                }
         
                         ///////////////////////VIEW ORDER/////////////////////////////

             public function generatePDFORDER($id)
             {
                 
                 $user_id = Auth::user()->id;
                 $getUser = User::get()->where('id', $user_id)->first();
                 //the above code needed for menu of header as per user
                 // if(Session::get($getUser->role_id)!==null){
                 // dd($id)    ;
                 $id = Crypt::decryptString($id);
        // dd( $id);
                 $uoGeneration = UOGenerationModel::get()->where('ein', $id)->first();
         
                 if (!$uoGeneration) {
                     return back()->with('message', 'Please fill the UO form'); // Redirect back with an alert message
                 }
                 //     //id is encrypted and send through URL
                 //    // Below ein is passed in the session
                //  if (session()->has('from_emp_ein')) {
                //      $session_ein = session()->get('from_emp_ein');
                //  }
                //  // set a session emp EIN 
                //  session()->put('from_emp_ein', $id);
                //  $ein = session()->get('from_emp_ein');
         
                 $data = ProformaModel::get()->where('ein', $id)->where('status', 6)->first();
         
                 $uoGenerationLast =  UOGenerationModel::orderBy('id', 'DESC')->first();
         
                
         
                 //GETTING DATA FROM number_generator table
                 $fix = UoNomenclatureModel::get()->first();
                 //dd($fix);
                 $prefixData = $fix->uo_format;
                 $yearData = $fix->year;
                 $suffixData = $fix->suffix;
         
                 if ($uoGenerationLast != null) {
                     $lastID = $uoGenerationLast->id;
                     $nextNumber = $lastID + 1;
         
                     $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                    // $getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                 } else {
                     $lastID = 0;
                     $nextNumber = $lastID + 1;
                    // $getUOFileNo  = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                     $getUOFileNo  = $prefixData . str_pad($nextNumber, (int)$fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                 }
         
                 $getDate = Carbon::now()->format('Y-m-d');
         
               
             //    save while pdf generation
             if($uoGeneration != null)
             $uoGeneration->update([     
                 'uo_number' => $getUOFileNo,        
                 'generated_by' => $getUser->name,
                 'generated_on' => $getDate
         
             ]   );
         
             
            
             
                 //dd($data);
                
                 $uoGenerated = UOGenerationModel::get()->where ('ein',$id)->first();
         
                 $Sign1 = Sign1Model::get()->where ('id',$uoGenerated->signing_authority_1)->first();
                 $Sign2 = Sign2Model::get()->where ('id',$uoGenerated->signing_authority_2)->first();
          
                 $html = view('admin.Form.generatePDFUO', [
                     'data' => $data,
                     'post' => $uoGenerated->post,
                     'grade' => $uoGenerated->grade,
                     'department' => $uoGenerated->department,
                     'relationship' => $uoGenerated->relationship,
                      'adfilenumber' =>$uoGenerated->ad_file_number, // Pass the ad_file_number to the view
                      'dpfilenumber' =>$uoGenerated->dp_file_number, // Pass the ad_file_number to the view
                      'dpsign1' =>$Sign1->authority_name, 
                      'adsign2' =>$Sign2->name,
    /////////////////////////////////////////////////////////////////     
            //      ])->render();
         
            //      $pdf = new Dompdf();
            //      $pdf->loadHtml($html); // Customize the content of the PDF as per your needs
         
            //      $pdf->setPaper('A4', 'portrait'); // Set the paper size and orientation
            //      $pdf->render();
         
            //      // Generate the PDF file and send it as a response
            //      return $pdf->stream('document.pdf', ['Attachment' => true]);
            //  }
            //this is for download
            /////////////////////////////////////////////////////////
            ])->render();

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"',
            ];
    
            $pdf = Pdf::loadHTML($html)->output();
    
            return Response::make($pdf, 200, $headers);
        }
         
         
             ///////////////////////////////////////////////////////////////////////////
         

    }

