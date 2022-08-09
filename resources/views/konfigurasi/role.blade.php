@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Role
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Monthly Sales</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" height="642" width="1388"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
