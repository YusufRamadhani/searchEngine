@extends('layouts/app')

@section('content')

<div class="container">
    <div class="d-flex col-md-10">
        <div class="card">
            <div class="card-body">
                <div class="conatiner">
                    <dl class="row">
                        <dt class="col-sm-3">Tanggal</dt>
                        <dd class="col-sm-9" id="date">{{ $result['date'] }}</dd>
                        <dt class="col-sm-3">Loglivechat</dt>
                        <dd class="col-sm-9" id="loglivechatid">{{ $result['loglivechatid'] }}</dd>
                        <dt class="col-sm-3">Visitor</dt>
                        <dd class="col-sm-9" id="visitor">{{ $result['visitor'] }}</dd>
                        <dt class="col-sm-3">Agent</dt>
                        <dd class="col-sm-9" id="agent">{{ $result['agent'] }}</dd>
                        <dt class="col-sm-3">Chat</dt>
                        @foreach($result['chat'] as $chat)
                        <dt class="col-sm-3"></dt>
                        <dd class="col-sm-9" id="chat">{{ $chat }}</dd>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection