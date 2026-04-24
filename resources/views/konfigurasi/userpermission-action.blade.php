<style>
    .permission-modern-modal {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
    }

    .permission-modern-modal .modal-header {
        border-bottom: 1px solid #e5edf8;
        background: linear-gradient(135deg, #f7fbff 0%, #eef5ff 100%);
    }

    .permission-modern-modal .modal-title {
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .permission-modern-modal .permission-grid {
        display: grid;
        gap: 10px;
    }

    .permission-modern-modal .permission-item {
        border: 1px solid #dbe7fb;
        border-radius: 12px;
        padding: 10px 12px;
        background: #f9fcff;
    }

    .permission-modern-modal .permission-item-title {
        font-weight: 600;
        color: #263a57;
        margin-bottom: 8px;
    }

    .permission-modern-modal .permission-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .permission-modern-modal .permission-check {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #d8e4f8;
    }

    .permission-modern-modal .permission-check .badge {
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: 999px;
        min-width: 22px;
    }

    body.dark .permission-modern-modal {
        background: #1a263a;
    }

    body.dark .permission-modern-modal .modal-header {
        border-bottom-color: rgba(160, 184, 217, 0.22);
        background: linear-gradient(135deg, #1e2d45 0%, #162236 100%);
    }

    body.dark .permission-modern-modal .permission-item {
        border-color: rgba(160, 184, 217, 0.22);
        background: #1f2d45;
    }

    body.dark .permission-modern-modal .permission-item-title {
        color: #e7efff;
    }

    body.dark .permission-modern-modal .permission-check {
        background: #1a263a;
        border-color: rgba(160, 184, 217, 0.25);
        color: #dbe8ff;
    }
</style>

<div class="modal-content permission-modern-modal">
    <form id="formAction" action="{{ route('userpermissions.update',$user->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $user->name }}">
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">Permission User: {{ $user->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @php
                $permissionsViaRole = $user->getPermissionsViaRoles()->pluck('name')->toArray();
                $assignablePermissions = $permissions->reject(function ($permissionName) use ($permissionsViaRole) {
                    return in_array($permissionName, $permissionsViaRole, true);
                });

                $permissionGroups = [];
                $permissionMap = array_flip($assignablePermissions->toArray());

                foreach($assignablePermissions as $value){
                    $url = explode('-',$value)[0];
                    foreach (['-create','-read','-update','-delete'] as $action) {
                        $permission_name = $url.$action;
                        if (in_array($permission_name, $assignablePermissions->toArray(), true)) {
                            if (!in_array($url,$permissionGroups)) {
                                array_push($permissionGroups,$url);
                            }
                        }
                    }
                }
            @endphp

            @if (count($permissionGroups) === 0)
                <div class="alert alert-light mb-0">
                    Semua permission user ini sudah tercakup dari role. Tidak ada permission tambahan untuk diatur langsung.
                </div>
            @else
                <div class="permission-grid">
                    @foreach($permissionGroups as $key => $url)
                        <div class="permission-item">
                            <div class="permission-item-title">{{ $url }}</div>
                            <div class="permission-actions" id="heading{{ $key }}">
                                @foreach (['create','read','update','delete'] as $action)
                                    @php
                                        $permissionName = $url.'-'.$action;
                                        $permissionId = $permissionMap[$permissionName] ?? null;
                                        $shortLabel = strtoupper(substr($action, 0, 1));
                                    @endphp

                                    @if ($permissionId)
                                        <label class="permission-check" for="user_permission_{{ $permissionId }}">
                                            <input
                                                type="checkbox"
                                                name="permission[]"
                                                value="{{ $permissionId }}"
                                                id="user_permission_{{ $permissionId }}"
                                                @checked(in_array($permissionId, $userPermissions))
                                            >
                                            <span class="badge bg-primary">{{ $shortLabel }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
