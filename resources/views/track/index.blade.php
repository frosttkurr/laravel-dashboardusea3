@extends('layouts.master')
@section('title') Track  @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') U-Sea @endslot
@slot('title') Track @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-10">
                        <h4 class="card-title">Track</h4>
                        <p class="card-title-desc">Data track biota yang tercatat</p>
                    </div>
                    
                    @can('track')
                    <div class="col-2 text-right">
                        <a href="{{ route('admin.dashboard.track.create') }}"><button type="button" class="mt-1 btn btn-primary waves-effect waves-light">Tambah Data</button></a>
                    </div>
                    @endcan
                </div>
                </div>
            <div class="card-body">

                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                    <tr>
                        <th class="col-1">No.</th>
                        <th class="col-4">Tanggal</th>
                        <th class="col-2">Status</th>
                        <th class="col-4">Action</th>
                    </tr>
                    </thead>


                    <tbody>
                    @foreach($tracks as $key => $track)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{date('d-M-Y', strtotime($track->tanggal))}}</td>
                        <td>
                            @if($track->is_valid == 0)
                                <span id="badge_status_{{ $track->id }}" class="badge badge-warning">Belum Valid</span>
                            @else
                                <span id="badge_status_{{ $track->id }}" class="badge badge-success">Valid</span>
                            @endif
                        </td>
                        <td>
                            @can('track')
                                @if (Auth::user()->roles[0]->id == 1)
                                    <a href="{{ route('admin.dashboard.track.edit', $track->id) }}"><button type="button" class="mt-1 btn btn-warning waves-effect waves-light">Edit</button></a>
                                    <a href="{{ route('admin.dashboard.track.destroy', $track->id) }}" onclick="notificationBeforeDelete(event, this)">
                                        <button type="button" class="mt-1 btn btn-danger waves-effect waves-light">Hapus</button>
                                    </a>
                                @endif
                            @endcan
                            <a href="{{ route('admin.dashboard.track.detail.index', $track->id) }}"><button type="button" class="mt-1 btn btn-secondary waves-effect waves-light">Detail</button></a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-buttons/datatables.net-buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>

<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>

<script>
    function notificationBeforeDelete(event, el) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin hapus data?',
            text: 'Data akan dihapus',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus',                
        }).then((result) => {
            if (result.value) {
                $("#delete-form").attr('action', $(el).attr('href'));
                $("#delete-form").submit();
            }
        })
    }

    function updateStatus(track_id){
        var getStatus = document.getElementById('status_track_' + track_id);
        var badgeStatus = document.getElementById('badge_status_' + track_id);
        
        fetch('/admin/dashboard/track/ajax-update/' + track_id, {
            method: 'patch',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                is_valid: getStatus.value
            })
        })
        .then(response => {
            return response.json()
        }) 
        .then(data => {
            if (data[0] == true) {
                if (data[1] == 0) {
                    badgeStatus.innerHTML = "Belum Valid";
                    badgeStatus.className = "badge badge-warning";
                } else if (data[1] == 1) {
                    badgeStatus.innerHTML = "Valid";
                    badgeStatus.className = "badge badge-success";
                }
                
                Swal.fire(
                    'Berhasil!',
                    'Status surat berubah',
                    'success'
                )
            } else {
                Swal.fire(
                    'Gagal!',
                    'Status surat tidak berubah',
                    'error'
                )
            }
        })
        .catch(error => console.log(error))
    }
</script>
@endsection
