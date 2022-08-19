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
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif

                            {!! Form::model($user, ['method' => 'PATCH','route' => ['userroles.update', $user->id]]) !!}
                                    {!! Form::hidden('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                    <div class="form-group mb-2 p-2">
                                            @foreach($roles as $value)
                                                <label>{{ Form::checkbox('roles[]', $value->id, in_array($value->id, $userRoles) ? true : false, array('class' => 'name')) }}
                                                {{ $value->name }}</label>
                                            <br/>
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
