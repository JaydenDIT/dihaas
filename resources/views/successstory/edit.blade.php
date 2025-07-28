@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center pt-5">
            <h1 class="display-one mt-5">Success Story</h1>
            <div class="text-left"><a href="/successstories" class="btn btn-outline-primary">Success Story List</a>
            </div>

            <form id="edit-frm" method="POST" action="" class="border p-3 mt-2" enctype="multipart/form-data">

			<div class="control-group col-6 text-left">
                    <label for="name">Success Story</label>
                    <div>
                        <input type="text" id="name" class="form-control mb-4" name="name"
                            placeholder="Enter name " value="{!! $dept->name !!}" required>
                    </div>
                </div>
                <div class="control-group col-6 text-left">
                    <label for="description">Success Story</label>
                    <div>
                        <input type="text" id="description" class="form-control mb-4" name="description"
                            placeholder="Enter description " value="{!! $dept->description !!}" required>
                    </div>
                </div>
                <div class="control-group col-6 text-left">
                    <label for="image">Image</label>
                    <input type="file" id="image" class="form-control mb-4" name="image" accept="image/*">
                </div>

        </div>
    </div>

    @csrf
    @method('PUT')
    <div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Save Update</button></div>
    </form>
</div>
</div>
</div>
@endsection