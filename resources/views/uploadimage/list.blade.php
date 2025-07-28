@extends('.layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center pt-5">
            <h1 class="display-one m-5">Success Story</h1>
            <div class="text-left"><a href="uploadimage/create" class="btn btn-outline-primary">Upload New Images</a></div>

            <table class="table mt-3  text-left">
                <thead>
                    <tr>
                        <th scope="col"> ID</th>
                     
                      
                        <th scope="col"> Image</th>
                        <th scope="col"> Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                        <?php
                                
                            use App\Models\UploadImageModel;

                            $image = UploadImageModel::orderBy('status', 'asc')->first(); 


                        ?>
                    @forelse($uploadimages as $uploadimage)
                    <tr>
                        <td>{!! $uploadimage->id !!}</td>
                      
                        <td>
                        <img src="{{ route('image.show', ['filename' => $uploadimage->image]) }}" alt="Your Image" height="200" width="200">
                        </td>
                        <td>{!! $uploadimage->status !!}</td>
                        <td style="display:flex; " class="gap-2"><a href="uploadimage/{!! $uploadimage->id !!}/edit"
                                class="btn btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-outline-danger ml-1 delete-btn"
                                data-id="{!! $uploadimage->id !!}">Delete</button>
                        </td>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No Images found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteConfirmationModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">Are you sure to delete this record?</div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default cancel-btn">Cancel</button>
                <form id="delete-frm" class="" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script nonce="{{ csp_nonce() }}">
document.addEventListener("DOMContentLoaded", function() {
    var deleteButtons = document.querySelectorAll(".delete-btn");
    var cancelButtons = document.querySelectorAll(".cancel-btn");

    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            var id = event.target.dataset.id;
            showModel(id);
        });
    });

    cancelButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            dismissModel();
        });
    });
});

function showModel(id) {
    var frmDelete = document.getElementById("delete-frm");
    frmDelete.action = 'uploadimage/delete/' + id;
    var confirmationModal = document.getElementById("deleteConfirmationModel");
    confirmationModal.style.display = 'block';
    confirmationModal.classList.remove('fade');
    confirmationModal.classList.add('show');
}


function dismissModel() {
    var confirmationModal = document.getElementById("deleteConfirmationModel");
    confirmationModal.style.display = 'none';
    confirmationModal.classList.remove('show');
    confirmationModal.classList.add('fade');
}
</script>
@endsection