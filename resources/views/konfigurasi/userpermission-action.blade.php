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
            <div class="accordion mb-3" id="permission-accordion">
                @php
                    $permission = [];
                    foreach($permissions as $key => $value){
                        $dash = strpos($value,'-');
                        $url = substr($value,0,$dash);
                        foreach (['create','read','update','delete'] as $action) {
                            $permission_name = $url.'-'.$action;
                            if (in_array($permission_name,$permissions->toArray())) {
                                if (!in_array($url,$permission)) {
                                    array_push($permission,$url);
                                }
                            }
                        }
                    }
                @endphp
                @foreach($permission as $key => $url)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $key }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                            aria-controls="collapse{{ $key }}">
                            {{ $url }}
                        </button>
                    </h2>
                    <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $key }}" data-bs-parent="#permission-accordion">
                        <div class="accordion-body row">
                            @foreach (['create','read','update','delete'] as $action)
                                @php
                                    $permission_name = $url.'-'.$action;
                                    $data = App\Models\Permission::where('name',$permission_name)->value('id');
                                @endphp
                                @if (in_array($permission_name,$permissions->toArray()))
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-text light">
                                            <input
                                                type="checkbox"
                                                name="permission[]"
                                                value="{{ $data }}"
                                                class="form-check-input mt-0"
                                                @checked(in_array($data, $userPermissions))
                                            >
                                        </div>
                                        <input type="text" class="form-control" value="{{ $action }}" aria-label="Text input with checkbox">
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <br/>
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
