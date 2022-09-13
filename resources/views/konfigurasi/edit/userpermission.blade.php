@extends('layouts.master')


@section('content')
<div class="main-content">
    <div class="title">
        {{ ucFirst(request()->segment(1)) }} {{ ucFirst(request()->segment(2)) }}
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>{{ $user->name }} Permissions</h2>
                            </div>

                            {!! Form::model($user, ['method' => 'PATCH','route' => ['userpermissions.update', $user->id]]) !!}
                                    {!! Form::hidden('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                    <div class="accordion mb-3" id="accordionExample">
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
                                            aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
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
                                                                    {{ Form::checkbox('permission[]', $data, in_array($data, $userPermissions) ? true : false, array('class' => 'form-check-input mt-0')) }}
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
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a class="btn btn-secondary" href="{{ route('users.index') }}">Cancel</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
