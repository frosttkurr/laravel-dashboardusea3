
<?php $__env->startSection('title'); ?> SIG  <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> U-Sea <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> SIG <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script>
    var defaultLatitude = -8.4512582;
    var defaultLongitude = 115.0783864;
    var map = L.map('map').setView([defaultLatitude, defaultLongitude], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var detailTracksData = <?php echo json_encode($trackDetails, 15, 512) ?>;
    var markers = {};

    detailTracksData.forEach(function(detailTrack) {
        var lat = detailTrack.latitude;
        var lng = detailTrack.longitude;
        var imageSrc = "<?php echo e(url('storage/')); ?>/" + detailTrack.image;
        <?php if(Route::is('dashboard.*')): ?>
            var routeUrl = "<?php echo e(route('dashboard.track.detail.show', ['id_track', 'id'])); ?>";
        <?php elseif(Route::is('admin.*')): ?>
            var routeUrl = "<?php echo e(route('admin.dashboard.track.detail.show', ['id_track', 'id'])); ?>";
        <?php endif; ?>
        
        routeUrl = routeUrl.replace('id_track', detailTrack.id_track).replace('id', detailTrack.id);
        var marker = L.marker([lat, lng]).addTo(map);

        var popupContent = `
            <div class="popup-container">
                <h6 class="popup-title">Track Details</h6>
                <ul>
                    <li><b>Biota:</b> ${detailTrack.biota.nama_biota}</li>
                    <li><b>Lokasi:</b> ${detailTrack.lokasi.nama_lokasi}</li>
                    <li><b>Keterangan:</b> ${detailTrack.keterangan}</li>
                    <li><a href="${routeUrl}" target="_blank" class="popup-link">Klik untuk informasi detail</a></li>
                </ul>
                <div class="image-container">
                    <img src="${imageSrc}" alt="Gambar biota" width="150px">
                </div>
            </div>
        `;

        if (!markers[lat]) {
            markers[lat] = {};
        }

        if (!markers[lat][lng]) {
            markers[lat][lng] = [];
        }

        markers[lat][lng].push(popupContent);
    });

    for (var lat in markers) {
        for (var lng in markers[lat]) {
            var marker = L.marker([lat, lng]).addTo(map);

            var combinedPopupContent = markers[lat][lng].join('<hr>'); // Combine popup content

            marker.on('mouseover', function(e) {
                this.bindPopup(combinedPopupContent).openPopup();
            });
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Laravel\dashboardusea3\resources\views/sig/index.blade.php ENDPATH**/ ?>