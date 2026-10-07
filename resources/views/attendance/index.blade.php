@extends('layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Sistem Absensi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Map Section -->
    <div class="lg:col-span-2">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Peta Lokasi Gedung {{ $location->unit->name ?? 'Unit Anda' }}</h3>
            <div id="map" class="w-full h-96 rounded-lg border border-gray-200" style="position: relative; z-index: 1;"></div>
            <p class="text-gray-600 text-sm mt-4">
                <strong>Instruksi:</strong> Izinkan akses lokasi GPS untuk melakukan absensi. 
                @if(auth()->user()->role === 'guru')
                    Anda harus berada dalam radius {{ $location->radius ?? 10 }} meter dari gedung {{ $location->unit->name ?? 'unit Anda' }}.
                @else
                    Anda dapat melakukan absensi dari mana saja.
                @endif
            </p>
        </div>
    </div>

    <!-- Attendance Form Section -->
    <div>
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Absensi</h3>

            @if ($todayAttendance && $todayAttendance->check_in_time && $todayAttendance->check_out_time)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 font-semibold">Anda sudah check-in dan check-out</p>
                    <p class="text-green-700 text-sm mt-1">Check-in: {{ $todayAttendance->check_in_time->format('H:i:s') }}</p>
                    <p class="text-green-700 text-sm">Check-out: {{ $todayAttendance->check_out_time->format('H:i:s') }}</p>
                </div>
            @elseif ($todayAttendance && $todayAttendance->check_in_time)
                <form id="checkoutForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="checkout_latitude" name="latitude" value="">
                    <input type="hidden" id="checkout_longitude" name="longitude" value="">
                    <input type="hidden" id="checkout_photo" name="photo" value="">

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <p class="text-blue-800 font-semibold">Check-in berhasil</p>
                        <p class="text-blue-700 text-sm">{{ $todayAttendance->check_in_time->format('H:i:s') }}</p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm mb-2">Koordinat Anda</p>
                        <p class="text-gray-700 font-semibold">
                            Lat: <span id="userLat">-</span>
                        </p>
                        <p class="text-gray-700 font-semibold">
                            Long: <span id="userLong">-</span>
                        </p>
                    </div>

                    <div id="distanceInfo" class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm mb-2">Jarak dari Gedung</p>
                        <p class="text-gray-700 font-semibold">
                            <span id="distance">-</span> meter
                        </p>
                    </div>

                    <button type="button" onclick="openCameraCheckout()" class="btn-primary w-full">
                        Ambil Foto & Check-Out
                    </button>

                    <div id="errorMessage" class="hidden p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm"></div>
                </form>
            @else
                <form id="checkinForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="latitude" name="latitude" value="">
                    <input type="hidden" id="longitude" name="longitude" value="">
                    <input type="hidden" id="checkin_photo" name="photo" value="">

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm mb-2">Koordinat Anda</p>
                        <p class="text-gray-700 font-semibold">
                            Lat: <span id="userLat">-</span>
                        </p>
                        <p class="text-gray-700 font-semibold">
                            Long: <span id="userLong">-</span>
                        </p>
                    </div>

                    <div id="distanceInfo" class="hidden text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm mb-2">Jarak dari Gedung</p>
                        <p class="text-gray-700 font-semibold">
                            <span id="distance">-</span> meter
                        </p>
                    </div>

                    <button type="button" onclick="openCameraCheckin()" class="btn-primary w-full" id="checkinBtn" disabled>
                        Ambil Foto & Check-In
                    </button>

                    <div id="errorMessage" class="hidden p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm"></div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div id="cameraModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="z-index: 9999;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ambil Foto</h3>
            
            <p class="text-sm text-gray-600 mb-4">
                <strong>Tips:</strong> Jika kamera tidak muncul, pastikan:
                <ul class="list-disc list-inside mt-2 text-xs">
                    <li>Browser memiliki izin akses kamera</li>
                    <li>Kamera tidak sedang digunakan aplikasi lain</li>
                    <li>Gunakan HTTPS atau localhost</li>
                </ul>
            </p>
            
            <video id="cameraVideo" class="w-full rounded-lg mb-4 bg-black" playsinline></video>
            <canvas id="photoCanvas" class="hidden"></canvas>
            
            <div id="photoPreview" class="hidden mb-4">
                <img id="previewImage" src="" alt="Preview" class="w-full rounded-lg">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="capturePhoto()" id="captureBtn" class="btn-primary flex-1">
                    Ambil Foto
                </button>
                <button type="button" onclick="retakePhoto()" id="retakeBtn" class="btn-secondary flex-1 hidden">
                    Ambil Ulang
                </button>
                <button type="button" onclick="closeCameraModal()" class="btn-secondary flex-1">
                    Batal
                </button>
            </div>

            <button type="button" onclick="submitWithPhoto()" id="confirmBtn" class="btn-primary w-full mt-3 hidden">
                Konfirmasi & Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
// Initialize map
const map = L.map('map').setView([-6.2088, 106.8456], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

let userMarker = null;
let locationMarker = null;
let radiusCircle = null;
let userLocation = null;
const userRole = '{{ auth()->user()->role }}';

// Get user location
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                userLocation = { lat, lng };

                const userLatEl = document.getElementById('userLat');
                const userLngEl = document.getElementById('userLong');
                const latEl = document.getElementById('latitude');
                const lngEl = document.getElementById('longitude');
                const checkoutLatEl = document.getElementById('checkout_latitude');
                const checkoutLngEl = document.getElementById('checkout_longitude');

                if (userLatEl) userLatEl.innerText = lat.toFixed(6);
                if (userLngEl) userLngEl.innerText = lng.toFixed(6);
                if (latEl) latEl.value = lat;
                if (lngEl) lngEl.value = lng;
                if (checkoutLatEl) checkoutLatEl.value = lat;
                if (checkoutLngEl) checkoutLngEl.value = lng;

                if (userMarker) {
                    userMarker.setLatLng([lat, lng]);
                } else {
                    userMarker = L.circleMarker([lat, lng], {
                        radius: 8,
                        fillColor: '#3b82f6',
                        color: '#1e40af',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map).bindPopup('Lokasi Anda');
                }

                map.setView([lat, lng], 16);

                // Update distance
                if (userRole === 'guru') {
                    updateDistance(lat, lng);
                } else {
                    // Non-guru role bisa absen dari mana saja
                    const checkinBtn = document.getElementById('checkinBtn');
                    if (checkinBtn) {
                        checkinBtn.disabled = false;
                        checkinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            },
            function(error) {
                showError('Gagal mendapatkan lokasi. Pastikan GPS sudah diaktifkan.');
            }
        );
    } else {
        showError('Browser Anda tidak mendukung Geolocation');
    }
}

// Display location on map
function displayLocation() {
    const lat = {{ $location->latitude ?? -6.2088 }};
    const lng = {{ $location->longitude ?? 106.8456 }};
    const radius = {{ $location->radius ?? 10 }};

    locationMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup(`<strong>Gedung {{ $location->unit->name ?? 'Unit' }}</strong><br>Radius: ${radius}m`);
    
    radiusCircle = L.circle([lat, lng], {
        radius: radius,
        color: '#ef4444',
        fillColor: '#ef4444',
        fillOpacity: 0.1,
        weight: 2
    }).addTo(map);
}

// Calculate distance
function updateDistance(lat, lng) {
    const locationLat = {{ $location->latitude ?? -6.2088 }};
    const locationLng = {{ $location->longitude ?? 106.8456 }};
    const locationRadius = {{ $location->radius ?? 10 }};

    const distance = calculateDistance(locationLat, locationLng, lat, lng);
    
    const distanceEl = document.getElementById('distance');
    if (distanceEl) distanceEl.innerText = distance.toFixed(2);
    
    const distanceInfoEl = document.getElementById('distanceInfo');
    if (distanceInfoEl) distanceInfoEl.classList.remove('hidden');

    const checkinBtn = document.getElementById('checkinBtn');
    if (checkinBtn) {
        if (distance <= locationRadius) {
            checkinBtn.disabled = false;
            checkinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            const errorEl = document.getElementById('errorMessage');
            if (errorEl) errorEl.classList.add('hidden');
        } else {
            checkinBtn.disabled = true;
            checkinBtn.classList.add('opacity-50', 'cursor-not-allowed');
            const errorEl = document.getElementById('errorMessage');
            if (errorEl) {
                errorEl.classList.remove('hidden');
                errorEl.innerText = `Anda berada ${(distance - locationRadius).toFixed(2)}m di luar radius. Harap mendekati lokasi gedung.`;
            }
        }
    }
}

// Haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Perform check-in
async function performCheckin() {
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    const photoEl = document.getElementById('checkin_photo');
    
    if (!latEl || !lngEl) {
        showError('Lokasi tidak berhasil diambil');
        return;
    }

    const latitude = latEl.value;
    const longitude = lngEl.value;
    const photo = photoEl ? photoEl.value : '';

    if (!latitude || !longitude) {
        showError('Menunggu lokasi GPS...');
        return;
    }

    try {
        const response = await fetch('{{ route("attendance.checkin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: latitude,
                longitude: longitude,
                photo: photo
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            showError(data.message);
        }
    } catch (error) {
        showError('Terjadi kesalahan: ' + error.message);
    }
}

// Perform check-out
async function performCheckout() {
    const latEl = document.getElementById('checkout_latitude');
    const lngEl = document.getElementById('checkout_longitude');
    const photoEl = document.getElementById('checkout_photo');
    
    if (!latEl || !lngEl) {
        showError('Lokasi tidak berhasil diambil');
        return;
    }

    const latitude = latEl.value;
    const longitude = lngEl.value;
    const photo = photoEl ? photoEl.value : '';

    if (!latitude || !longitude) {
        showError('Menunggu lokasi GPS...');
        return;
    }

    try {
        const response = await fetch('{{ route("attendance.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: latitude,
                longitude: longitude,
                photo: photo
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            showError(data.message);
        }
    } catch (error) {
        showError('Terjadi kesalahan: ' + error.message);
    }
}

// Show error message
function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    if (errorEl) {
        errorEl.classList.remove('hidden');
        errorEl.innerText = message;
    }
}

// Camera functions
let cameraType = 'checkin';
let capturedPhoto = null;

function openCameraCheckin() {
    cameraType = 'checkin';
    openCameraModal();
}

function openCameraCheckout() {
    cameraType = 'checkout';
    openCameraModal();
}

function openCameraModal() {
    const modal = document.getElementById('cameraModal');
    modal.classList.remove('hidden');
    
    const video = document.getElementById('cameraVideo');
    
    // Try with constraints first
    const constraints = {
        video: {
            facingMode: 'user',
            width: { ideal: 1280 },
            height: { ideal: 720 }
        },
        audio: false
    };
    
    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            video.srcObject = stream;
            video.play();
        })
        .catch(error => {
            console.error('Camera error:', error);
            // Try with basic video constraint if advanced constraints fail
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(fallbackError => {
                    console.error('Fallback camera error:', fallbackError);
                    let errorMsg = 'Gagal mengakses kamera';
                    if (fallbackError.name === 'NotAllowedError') {
                        errorMsg = 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.';
                    } else if (fallbackError.name === 'NotFoundError') {
                        errorMsg = 'Kamera tidak ditemukan pada perangkat ini.';
                    } else if (fallbackError.name === 'NotReadableError') {
                        errorMsg = 'Kamera sedang digunakan oleh aplikasi lain.';
                    } else if (fallbackError.message) {
                        errorMsg = 'Gagal mengakses kamera: ' + fallbackError.message;
                    }
                    showError(errorMsg);
                    closeCameraModal();
                });
        });
}

function closeCameraModal() {
    const modal = document.getElementById('cameraModal');
    modal.classList.add('hidden');
    
    const video = document.getElementById('cameraVideo');
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    
    capturedPhoto = null;
    resetCameraUI();
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('photoCanvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    ctx.drawImage(video, 0, 0);
    capturedPhoto = canvas.toDataURL('image/jpeg', 0.8);
    
    const previewImage = document.getElementById('previewImage');
    previewImage.src = capturedPhoto;
    
    const photoPreview = document.getElementById('photoPreview');
    photoPreview.classList.remove('hidden');
    
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    
    video.classList.add('hidden');
    captureBtn.classList.add('hidden');
    retakeBtn.classList.remove('hidden');
    confirmBtn.classList.remove('hidden');
}

function retakePhoto() {
    capturedPhoto = null;
    resetCameraUI();
}

function resetCameraUI() {
    const video = document.getElementById('cameraVideo');
    const photoPreview = document.getElementById('photoPreview');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    
    video.classList.remove('hidden');
    photoPreview.classList.add('hidden');
    captureBtn.classList.remove('hidden');
    retakeBtn.classList.add('hidden');
    confirmBtn.classList.add('hidden');
}

function submitWithPhoto() {
    if (!capturedPhoto) {
        showError('Foto belum diambil');
        return;
    }
    
    if (cameraType === 'checkin') {
        const photoInput = document.getElementById('checkin_photo');
        if (photoInput) {
            photoInput.value = capturedPhoto;
        }
    } else {
        const photoInput = document.getElementById('checkout_photo');
        if (photoInput) {
            photoInput.value = capturedPhoto;
        }
    }
    
    closeCameraModal();
    
    if (cameraType === 'checkin') {
        performCheckin();
    } else {
        performCheckout();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        displayLocation();
        getUserLocation();
    }, 100);
});
</script>
@endsection
