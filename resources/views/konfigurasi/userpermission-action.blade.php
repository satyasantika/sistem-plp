<div class="modal-content">
    <form id="formAction" action="{{ route('userpermissions.update',$user->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $user->name }}">
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">Edit {{ $user->name }} Permissions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @php
            // list permission
                $permission = [];
                foreach($permissions as $value){
                    $url = explode('-',$value)[0];
                    foreach (['-create','-read','-update','-delete'] as $action) {
                        $permission_name = $url.$action;
                        if (in_array($permission_name,$permissions->toArray())) {
                            if (!in_array($url,$permission)) {
                                array_push($permission,$url);
                            }
                        }
                    }
                }
            @endphp
            {{-- cek kecocokan setiap permission yang langsung dari Role --}}
            @foreach($permission as $key => $url)
            <div class="row">
                <div class="col col-md-4">
                    @foreach (['create','read','update','delete'] as $action)
                        @php
                            $permission_name = $url.'-'.$action;
                            $data = App\Models\Permission::where('name',$permission_name)->value('id');
                        @endphp
                        @if (in_array($permission_name,$permissions->toArray()))
                            <input
                                type="checkbox"
                                name="permission[]"
                                value="{{ $data }}"
                                id="{{ $data }}"
                                @checked(in_array($data, $userPermissions))
                            >
                            <label for="{{ $data }}">{{ ucwords(substr($action,0,1)) }}</label>
                        @endif
                    @endforeach
                </div>
                <div class="col col-md-8" id="heading{{ $key }}">
                    {{ $url }}
                </div>
            </div>
            @endforeach
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
