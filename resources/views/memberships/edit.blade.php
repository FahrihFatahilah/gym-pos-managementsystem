@extends('layouts.app')

@section('title', 'Edit Membership')
@section('page-title', 'Edit Membership')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Edit Membership</h6>
            </div>
            <div class="card-body">
                <p>Form edit membership dalam pengembangan</p>
                <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection