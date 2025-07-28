<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<style>
    .container {
        display: flex;
        /* justify-content: space-between; */
    }

    .inner-div {
        padding: 10px;
        width: 50%;
        /* Adjust as needed */
        /* height:500px; */
        box-sizing: border-box;
    }

    .card{

        height:100%;
    }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="inner-div">
    <div class="card">

    <div class="card-header">
       <h3  class="text-center" >Vacancies as per CMIS</h3>

    </div>
      <div class="card-body">
        <!-- <h5 class="card-title">As per CMIS</h5> -->
        <table class="table mt-3 text-left">
				<thead>
                <tr>
                                
                                <th>Post Name</th>
                                <th>Sanctioned Post</th>
                                <th>Employee count</th>
                                <th>Post Vacancy</th> 
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vacanciesCMIS as $vacancy)
                            <tr>
                              
                                <td>{{ $vacancy['designation'] }}</td>
                                <td>{{ $vacancy['post_count'] }}</td>
                                <td>{{ $vacancy['emp_cnt'] }}</td>
                                
                                <td>{{ ($vacancy['post_count']-$vacancy['emp_cnt']) }}</td>                                                             
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>



      </div>


    </div>
  </div>
  <div class="inner-div">
    <div class="card">
    <div class="card-header">
            <h3 class="text-center">Add Vacancy</h3>

        </div>

      <div class="card-body">
        <div>
        <form action="{{ route('vacancy_update.submit') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="vacancy_year" class="col-sm-6 col-form-label">Vacancy year/As on</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control" id="vacancy_year" name="vacancy_year"
                                value="{{ old('vacancy_year') }}">
                            @error('vacancy_year')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="post_name" class="col-sm-6 col-form-label">Name of the Post</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="post_name" name="post_name"
                                value="{{ old('post_name') }}">
                            @error('post_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sanctioned_post" class="col-sm-6 col-form-label">Total Sanctioned Post</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="sanctioned_post" name="sanctioned_post"
                                value="{{ old('sanctioned_post') }}">
                            @error('sanctioned_post')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="vacancy_direct" class="col-sm-6 col-form-label">Vacancy for Direct Recruitment</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="vacancy_direct" name="vacancy_direct"
                                value="{{ old('vacancy_direct') }}">
                            @error('vacancy_direct')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="employees_under_dih" class="col-sm-6 col-form-label">No. of Employees working under
                            DIH</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="employees_under_dih"
                                name="employees_under_dih" value="{{ old('employees_under_dih') }}">
                            @error('employees_under_dih')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="post_vacant_under_dih" class="col-sm-6 col-form-label">No. of Post vacant under
                            DIH</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="post_vacant_under_dih"
                                name="post_vacant_under_dih" value="{{ old('post_vacant_under_dih') }}">
                            @error('post_vacant_under_dih')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    

                    <br><br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                        &nbsp;&nbsp;&nbsp;&nbsp; 
                     <a href="{{ route('home') }}" class="btn btn-success">Back</a>
                    </div>
                </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
</body>
</html>