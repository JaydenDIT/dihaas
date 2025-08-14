 <form action="#" name="proforma-step1" id="proforma-step1">
     @csrf
     @if($action == 'create')
     <input type="hidden" name="action" id="action" value="create">
     @else
     <input type="hidden" name="action" id="action" value="update">
     <input type="hidden" name="proforma_id" id="proforma_id" value="{{ $proforma?->proforma_id }}">
     @endif

     <div class="row bg-pink mt-3">
         @include('proforma.formParts.form1._1deceased_cmis_details');
         @include('proforma.formParts.form1._2deceased_entry_details');
         @include('proforma.formParts.form1._3applicant_details');
         @include('proforma.formParts.form1._4applicant_address_current');
         @include('proforma.formParts.form1._5applicant_address_permanent');
         @include('proforma.formParts.form1._6prefer_post_parent_department');
         @include('proforma.formParts.form1._7prefer_post_other_department');



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
                 <button class="btn btn-md btn-success" id="saveProforma" type="button">Save</button>
                 @if($current_step >= 1)
                 <button class="btn btn-md btn-success nextBtn" type="button" data-step="1">Next</button>
                 @endif
             </div>
         </div>
     </div>
 </form>