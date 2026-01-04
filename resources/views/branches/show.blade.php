@extends('layouts.app')

@section('title', 'Detail Cabang')
@section('page-title', 'Detail Cabang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Detail Cabang</h6>
            </div>
            <div class="card-body">
                <p>Detail cabang dalam pengembangan</p>
                <a href="{{ route('branches.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection