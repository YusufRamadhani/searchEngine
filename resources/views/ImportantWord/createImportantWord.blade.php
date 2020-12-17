@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="container col-md-10">
        <div class="container m-auto" id="createForm">
            <div class="card">
                <h5 class="card-header">Featured</h5>
                <div class="card-body">
                    <form method="POST" action="{{ route('importantword.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="inputWord">Word</label>
                            <input type="text" class="form-control" id="inputWord" name="word">
                        </div>
                        <div class="form-group">
                            <label for="inputMainWord">Main Word</label>
                            <input type="text" class="form-control" id="inputMainWord" name="main_word">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection