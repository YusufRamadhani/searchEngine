<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-body">
                    <form action="/search" method="post">
                        {{ csrf_field() }}
                        <h5 class="card-title">Pencarian Chat</h5>
                        <div class="form-group row">
                            <div class="input-daterange input-group mb-3 mx-3 w-50" id="datepicker">
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
                            <div class="input-group my-3 mx-3">
                                <input type="text" class="form-control" name="query" placeholder="Search query" aria-label="Search query" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Cari</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>