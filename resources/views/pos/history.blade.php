@extends('layouts.app')

@section('title', 'History Transaksi')
@section('page-title', 'History Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">History Transaksi</h6>
            </div>
            <div class="card-body">
                <p>History transaksi dalam pengembangan</p>
                <a href="{{ route('pos.index') }}" class="btn btn-secondary">Kembali ke POS</a>
            </div>
        </div>
    </div>
</div>
@endsection