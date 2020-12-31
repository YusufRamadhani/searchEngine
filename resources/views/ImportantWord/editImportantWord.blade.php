@extends('layouts.dashboard')

@section('content')
<script>
    var myModal = document.getElementById('myModal')
    var myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', function() {
        myInput.focus()
    })
</script>
<div class="container">
    <div class="row justify-content-center my-5">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Word</th>
                    <th scope="col">Main Word</th>
                    <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                @foreach($importantWord as $value)
                <tr>
                    <th scope="row">{{$value->id}}</th>
                    <td>{{$value->word}}</td>
                    <td>{{$value->main_word}}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{$value->word}}">
                            edit
                        </button>
                        <div class="modal fade" id="editModal{{$value->word}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('importantword.update', $value->word) }}" id="edit_word">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Important Word</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <fieldset disabled>
                                                <div class="mb-3">
                                                    <label for="word" class="form-label">Word</label>
                                                    <input type="text" class="form-control" id="word" name="word" value="{{$value->word}}" placeholder="{{$value->word}}">
                                                </div>
                                            </fieldset>
                                            <div class="mb-3">
                                                <label for="main_word" class="form-label">Main Word</label>
                                                <input type="text" class="form-control" id="main_word" name="main_word">
                                            </div>
                                        </div>
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$value->word}}">
                            delete
                        </button>
                        <div class="modal fade" id="deleteModal{{$value->word}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('importantword.destroy', $value->word) }}" id="delete_word">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Important Word</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda yakin akan menghapus kata</p>
                                            <fieldset disabled>
                                                <div class="mb-3">
                                                    <label for="word" class="form-label">Word</label>
                                                    <input type="text" class="form-control" id="word" value="{{$value->word}}" placeholder="{{$value->word}}">
                                                </div>
                                            </fieldset>
                                            @csrf
                                            @method('DELETE')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $importantWord->links() }}
    </div>
</div>
@endsection