@extends('layouts.master')
@section('title') Kondisi Perairan  @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') U-Sea @endslot
@slot('title') Kondisi Perairan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-10">
                        <h4 class="card-title">Kondisi Perairan</h4>
                        <p class="card-title-desc">Data kondisi perairan yang tercatat</p>
                    </div>
                    
                    @can('kondisi-perairan')
                        <div class="col-2 text-right">
                            <a href="{{ route('admin.dashboard.kondisi-perairan.create') }}"><button type="button" class="mt-1 btn btn-primary waves-effect waves-light">Tambah Data</button></a>
                        </div>
                    @endcan
                </div>
                </div>
            <div class="card-body">

                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                        <tr>
                            <th class="col-1">No.</th>
                            <th class="col-2">Tanggal</th>
                            <th class="col-2">Lokasi</th>
                            <th class="col-2">Kondisi</th>
                            <th class="col-4">Uraian</th>
                            <th class="col-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($kondisiPerairans as $key => $kondisi)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{date('d-M-Y', strtotime($kondisi->tanggal))}}</td>
                                <td>{{$kondisi->lokasi->nama_lokasi}}</td>
                                <td>{{$kondisi->kondisi}}</td>
                                <td>{{$kondisi->uraian}}</td>
                                <td>
                                    <a href="{{ route('admin.dashboard.kondisi-perairan.edit', $kondisi->id) }}"><button type="button" class="mt-1 btn btn-warning waves-effect waves-light">Edit</button></a>
                                    <a href="{{ route('admin.dashboard.kondisi-perairan.destroy', $kondisi->id) }}" onclick="notificationBeforeDelete(event, this)">
                                        <button type="button" class="mt-1 btn btn-danger waves-effect waves-light">Hapus</button>
                                    </a>
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
</script>
@endsection
