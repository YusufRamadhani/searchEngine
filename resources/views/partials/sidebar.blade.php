<div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading">Menu Admin </div>
    <div class="list-group list-group-flush list-group-root well">
        <!-- <a href="#" class="list-group-item list-group-item-action bg-light">Important Word</a> -->
        <a href="#item-1" class="list-group-item list-group-item-action bg-light" data-toggle="collapse">
            <i class="glyphicon glyphicon-chevron-right"></i>Important Word
        </a>
        <div class="list-group list-group-flush collapse" id="item-1">
            <a href="{{ route('importantword.index') }}" class="list-group-item list-group-item-action bg-light">Index Important Word</a>
            <a href="{{ route('edit.importantword') }}" class="list-group-item list-group-item-action bg-light">Kelola Important Word</a>
            <a href="{{ route('importantword.create') }}" class="list-group-item list-group-item-action bg-light">Perbarui Important Word</a>
        </div>
        <a href="{{ route('index.term') }}" class="list-group-item list-group-item-action bg-light">Index Term</a>
    </div>
</div>