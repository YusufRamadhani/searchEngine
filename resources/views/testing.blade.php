@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
@parent

<p>This is appended to the master sidebar.</p>
@endsection

@section('content')
<p>This is my body content.</p>
<br>
<p>{{$data->word}}</p>
<p>{{$data->is_usage}}</p>

@endsection