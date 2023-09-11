@extends('layouts.master')
@section('title') SIG  @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') U-Sea @endslot
@slot('title') SIG @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-10">
                        <h4 class="card-title">SIG</h4>
                        <p class="card-title-desc">Peta Persebaran Data Track Biota Laut</p>
                    </div>
                </div>
                </div>
            <div class="card-body">
                <div id="map" style="height:600px;"></div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
@section('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script>
    var defaultLatitude = -8.4512582;
    var defaultLongitude = 115.0783864;
    var map = L.map('map').setView([defaultLatitude, defaultLongitude], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var detailTracksData = @json($trackDetails);
    detailTracksData.forEach(function(detailTrack) {
        var lat = detailTrack.latitude;
        var lng = detailTrack.longitude;
        var imageSrc = "{{ url('storage/') }}/" + detailTrack.image;
        var marker = L.marker([lat, lng]).addTo(map);

        var popupContent = `
            <div class="popup-container">
                <h4 class="popup-title">Track Details</h4>
                <ul>
                    <li><b>Biota:</b> ${detailTrack.biota.nama_biota}</li>
                    <li><b>Lokasi:</b> ${detailTrack.lokasi.nama_lokasi}</li>
                    <li><b>Keterangan:</b> ${detailTrack.keterangan}</li>
                </ul>
                <div class="image-container">
                    <img src="${imageSrc}" alt="Gambar biota" width="150px">
                </div>
            </div>
        `;

        marker.on('mouseover', function(e) {
            this.bindPopup(popupContent).openPopup();
        });

        marker.on('click', function(e) {
            var routeUrl = "{{ route('admin.dashboard.track.detail.show', ['id_track', 'id']) }}";
            routeUrl = routeUrl.replace('id_track', detailTrack.id_track).replace('id', detailTrack.id);
            window.open(routeUrl, '_blank');
        });
    
        marker.bindTooltip("Click for details");
    });
</script>
@endsection