<div class="container">
    <div class="col-xl-10">
        @foreach($result as $key => $value)
        <li>ID Dokumen = {{$key}}</li>
        <li>hasil cosine similarity = {{$value}}</li>
        @endforeach
    </div>
</div>