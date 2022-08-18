<div class="modal-content">
    <form id="formAction" action="{{ $role->id ? route('roles.update',$role->id) : route('roles.store') }}" method="post">
        @csrf
        @if ($role->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $role->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Name</label>
                        <input type="text" placeholder="Role name" value="{{ $role->name }}" name="name" class="form-control" id="roleName" required autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="guardName" class="form-label">Guard</label>
                        <input type="text" placeholder="Guard name" value="{{ $role->guard_name }}" name="guard_name" class="form-control" id="guardName">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
