@extends('layouts.dashboard')

@section('content')
<div class="container col-md-10">
    <div class="card m-auto">
        <h5 class="card-header">Indeks</h5>
        <div class="card-body">
            <p class="font-weight-light">Masukkan rentang waktu chat yang ingin di buatkan indeks <br></p>
            <form action="{{ route('create.term') }}" method="post">
                {{ csrf_field() }}

                <div class="form-group row">
                    <div class="input-daterange input-group w-50" id="datepicker">
                        <input type="text" class="input-sm form-control" name="start" placeholder="Start" />
                        <span class="input-group-text">to</span>
                        <input type="text" class="input-sm form-control" name="end" placeholder="End" />
                    </div>
                    <script type="text/javascript">
                        $('.input-daterange').datepicker({
                            format: 'mm-dd-yyyy',
                            todayBtn: true,
                            daysOfWeekHighlighted: "0"
                        });
                    </script>

                </div>
                <button class="btn btn-primary mt-3" type="submit" value="Submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="card">
        <img class="card-img-top" src="holder.js/100x180/" alt="">
        <div class="card-body">
            <form action="{{ route('create.doc') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Document
                </button>
            </form>
        </div>
    </div>
</div>
@endsection