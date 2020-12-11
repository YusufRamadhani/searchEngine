@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
@parent

<p>This is appended to the master sidebar.</p>
@endsection

@section('content')
<p>This is my body content.</p>
<br>
<p>ini tanggal mulai <?= date_format($startDate, "Y/m/d"); ?></p>
<br>
<p>ini tanggal berakhir <?= date_format($endDate, "Y/m/d"); ?></p>

@endsection