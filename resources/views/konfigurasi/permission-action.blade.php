<div class="modal-content">
    <form id="formAction" action="{{ $permission->id ? route('permissions.update',$permission->id) : route('permissions.store') }}" method="post">
        @csrf
        @if ($permission->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $permission->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Name</label>
                        <input type="text" placeholder="Permission name" value="{{ $permission->name }}" name="name" class="form-control" id="permissionName" required autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="guardName" class="form-label">Guard</label>
                        <input type="text" placeholder="Guard name" value="{{ $permission->guard_name }}" name="guard_name" class="form-control" id="guardName">
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
