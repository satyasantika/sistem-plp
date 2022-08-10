<div class="modal-content">
    <form id="formAction" action="{{ route('roles.update',$role->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Name</label>
                        <input type="text" placeholder="Role name" value="{{ $role->name }}" name="name" class="form-control" id="roleName">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="guardName" class="form-label">Guard</label>
                        <input type="text" placeholder="Role name" value="{{ $role->guard_name }}" name="guard_name" class="form-control" id="guardName">
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
