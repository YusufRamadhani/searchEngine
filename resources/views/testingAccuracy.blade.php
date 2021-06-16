@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <form action="/search/test" method="post">
                        {{ csrf_field() }}
                        <h5 class="card-title">Pencarian Chat</h5>
                        <div class="form-group row">
                            <div class="input-group p-2 my-3 ">
                                <input type="text" class="form-control" name="query" placeholder="Search query" aria-label="Search query" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Cari</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- nanti dibuat pagination dibawah list hasil pencarian -->

            @isset($result)
            <ul class="list-group list-group-flush">
                @foreach($result as $value)
                <a href="{{ route('chat_test.show',['loglivechatid' => $value['id']]) }}" class="list-group-item list-group-item-action">
                    <p class="mb-1">{{$value['chat']}}</p>
                    <small class="text-muted">{{ $value['date'] }} &nbsp; visitor: {{ $value['visitor'] }} &nbsp; score: {{ $value['score'] }}</small>
                </a>
                @endforeach
            </ul>
            @endisset
            <!-- buat pagination -->
        </div>
    </div>
</div>
@endsection