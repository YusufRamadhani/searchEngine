@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center my-5">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Word</th>
                    <th scope="col">Main Word</th>
                </tr>
            </thead>
            <tbody>
                @foreach($importantWord as $value)
                <tr>
                    <th scope="row">{{$value->id}}</th>
                    <td>{{$value->word}}</td>
                    <td>{{$value->main_word}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $importantWord->links() }}
    </div>
</div>
@endsection