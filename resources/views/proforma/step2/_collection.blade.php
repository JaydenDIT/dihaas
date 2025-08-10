 <form action="#" name="upload_form" id="upload_form">
     @csrf
     @if($action == 'create')
     <input type="hidden" name="action" id="action" value="create">
     @else
     <input type="hidden" name="action" id="action" value="update">
     <input type="hidden" name="proforma_id" id="proforma_id" value="{{ $proforma?->proforma_id }}">
     @endif

     <div class="row bg-pink">


         @include('proforma.step2._1document_upload');

         <!-- I agree -->
         <div class="row mb-4 ps-5 mt-5">
             <div class="form-check  ps-5">
                 <input class="form-check-input" type="checkbox" name="flexCheckDefault" id="flexCheckDefault"
                     required>
                 <label class="form-check-label" for="flexCheckDefault">
                     I agree all the above information are correct. I also undertake that the remaining mandatory
                     document will be uploaded.
                 </label>
             </div>

         </div>

         <div class="hstack gap-3 my-3 p-3">


             <div class="ms-auto">
                 <button class="btn btn-md btn-success submitBtn" type="button" data-status="20">SUBMIT</button>
             </div>
         </div>
     </div>
 </form>