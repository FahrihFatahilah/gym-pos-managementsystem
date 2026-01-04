@extends('layouts.app')

@section('title', 'Member Expired')
@section('page-title', 'Member Expired')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-user-clock me-2"></i>
                    Daftar Member Expired
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Membership Terakhir</th>
                                <th>Berakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiredMembers as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>{{ $member->email ?: '-' }}</td>
                                    <td>
                                        @if($member->memberships->first())
                                            <span class="badge bg-info">
                                                {{ $member->memberships->first()->getTypeLabel() }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($member->memberships->first())
                                            {{ $member->memberships->first()->end_date->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('members.renew', $member) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-redo"></i> Perpanjang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="text-success">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                                            <p>Tidak ada member yang expired</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $expiredMembers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection