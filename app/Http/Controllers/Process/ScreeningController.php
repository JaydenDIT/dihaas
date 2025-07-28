<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Library\VideoStream;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;

use App\Models\ScreeningModel;
use Exception;

class ScreeningController extends Controller
{
    //view report

    public function viewScreeningReport(Request $request){
        $screenings = ScreeningModel::getData()->get();
        return view('screening.view', compact('screenings'));          
    }


    public function newNotification(Request $request){
        $screenings = ScreeningModel::getData()->get();
        return view('screening.new', compact('screenings'));   

       
    }


    public function saveNotification(Request $request){

        $screening_id = $request->post("screening_id","");  
        
        $rules = array(
            'screening_id' => ['sometimes', 'integer', 'exists:screening_report,screening_id'],
            'document_caption' => ['required', 'string', 'max:75'], 
            'document' => ['sometimes', 'mimetypes:application/pdf', 'max:5120'],
            'validity' => ['required', 'date']
        ); 

        if( $screening_id  == ""){ //for new
            $rules['document' ] = ['required', 'mimetypes:application/pdf', 'max:5120'];
        }

        $niceName = [
            'screening_id' => "Screening Report",
            'document_caption' => "Caption",
            'document' => "PDF File",
            'validity' => "Validity",
        ];        
       
        Validator::make($request->all(), $rules, [], $niceName )->validate();
        
        try{
            DB::beginTransaction();           

            if( $screening_id  == "" || $screening_id  == null){
                $resp = ScreeningModel::create([
                    'document_caption' => $request->post("document_caption"),
                    'validity' => date(  'Y-m-d'  , strtotime( $request->post("validity") ) )
                ]);
            }
            else{
                $resp = ScreeningModel::find( $screening_id );
                $resp->document_caption = $request->post("document_caption");
                $resp->validity = date(  'Y-m-d'  , strtotime( $request->post("validity") ) );
                $resp->save();
            }
             

            if ($request->hasFile('document')) {
                $supporting_file = 'document';
                $application_detail_id = $this->saveDocument($request, $supporting_file, $resp);               
                if(!$application_detail_id) {
                    throw new Exception("Failed to add");
                }
            }
        }
        catch (QueryException $e) {
           // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
        DB::commit();
        return redirect()->back()->withSuccess('Successfully Save');
    }


 
    public function deleteNotification(Request $request, $id){
        try{            
            DB::beginTransaction();
            ScreeningModel::where("screening_id", $id)
                ->update(['is_deleted'=>date('Y-m-d H:i:s')]);
           
        }
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
        DB::commit();
        return redirect()->back()->withSuccess('Successfully Deleted');
    }


    
    //save document of storage path
    public function saveDocument(Request $request, $supporting_file, $resp){
        try{
            if ($request->hasFile($supporting_file)) {

                $file = $request->file($supporting_file);
                if ($file) {
                    $dir = 'screening/'.$resp->screening_id;
                    $filename = $file->getClientOriginalName();                        
    
                    $fileUrl = $request->file($supporting_file)->storeAs(
                        $dir,  $filename
                    );
                    $fileDir = storage_path('app/'.$dir.'/'.$filename);
                    $exists = File::exists($fileDir);
                    //this below code is giving error consult Malem
                   /* if($exists){
                        throw new Exception("Failed to add");
                    }*/
                    $resp->document_link = $fileUrl ;
                    $resp->document_type = "pdf" ;
                    
                    $resp->save();
                }
            }
        }
        catch(Exception $e){
            return false;
        }
        return true;
    }










    
    //load document of storage path
    public function getdocNotification(Request $request, $doc_id){
        $resp = ScreeningModel::find($doc_id);
        $url = $resp->document_link;

        switch($resp->document_type){
            case "pdf" :
                return response()->make(file_get_contents(Storage::path($url)), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline;'
                ]);
            break;
            case "video" :
                $video_path = Storage::path($url);
                $stream = new VideoStream($video_path);
                return response()->stream(function() use ($stream) {
                    $stream->start();
                });
            break;
            case "image" :
                return response()->make(file_get_contents(Storage::path($url)), 200, [
                    'Content-Type' => 'image',
                    'Content-Disposition' => 'inline;'
                ]);
            break;
        }
    }

















}