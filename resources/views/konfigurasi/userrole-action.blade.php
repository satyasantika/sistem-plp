<div class="modal-content">
    <form id="formAction" action="{{ route('userroles.update',$user->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $user->name }}">
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">Edit {{ $user->name }} Roles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row mb-2">
                @foreach ($roles as $value)
                    <div class="col-md-3">
                        <div class="input-group mb-2">
                            <div class="input-group-text light">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $value->id }}"
                                    class="form-check-input mt-0"
                                    @checked(in_array($value->id, $userRoles))
                                >
                            </div>
                            <input type="text" class="form-control" value="{{ $value->name }}" aria-label="Text input with checkbox">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
