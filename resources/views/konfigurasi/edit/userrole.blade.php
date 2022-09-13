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
                                <h2>{{ $user->name }} Roles</h2>
                            </div>

                            {!! Form::model($user, ['method' => 'PATCH','route' => ['userroles.update', $user->id]]) !!}
                                {!! Form::hidden('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                <div class="row mb-2">
                                    @foreach ($roles as $value)
                                        <div class="col-md-3">
                                            <div class="input-group mb-2">
                                                <div class="input-group-text light">
                                                    {{ Form::checkbox('roles[]', $value->id, in_array($value->id, $userRoles) ? true : false, array('class' => 'form-check-input mt-0')) }}
                                                </div>
                                                <input type="text" class="form-control" value="{{ $value->name }}" aria-label="Text input with checkbox">
                                            </div>
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
