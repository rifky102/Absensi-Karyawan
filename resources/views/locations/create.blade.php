@extends('layouts.app')

@section('title', 'Tambah Lokasi')
@section('page-title', 'Tambah Lokasi Geofencing')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Map Section -->
    <div class="lg:col-span-2">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Lokasi di Map</h3>
            <div id="map" class="w-full h-96 rounded-lg border border-gray-200"></div>
            <p class="text-gray-600 text-sm mt-4">
                <strong>Instruksi:</strong> Klik di peta untuk memilih titik lokasi gedung unit. 
                Koordinat akan otomatis terisi di form di sebelah kanan.
            </p>
        </div>
    </div>

    <!-- Form Section -->
    <div>
        <div class="card">
            <form action="{{ route('locations.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="unit_id" class="block text-gray-700 font-semibold mb-2">
                        Unit <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="unit_id" 
                        name="unit_id" 
                        class="input-field @error('unit_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Nama Lokasi <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="input-field @error('name') border-red-500 @enderror"
                        placeholder="Contoh: Gedung TK"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="latitude" class="block text-gray-700 font-semibold mb-2">
                        Latitude <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="latitude" 
                        name="latitude" 
                        value="{{ old('latitude') }}"
                        class="input-field @error('latitude') border-red-500 @enderror"
                        placeholder="Contoh: -6.123456"
                        step="0.000001"
                        required
                        readonly
                    >
                    @error('latitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-gray-700 font-semibold mb-2">
                        Longitude <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="longitude" 
                        name="longitude" 
                        value="{{ old('longitude') }}"
                        class="input-field @error('longitude') border-red-500 @enderror"
                        placeholder="Contoh: 106.123456"
                        step="0.000001"
                        required
                        readonly
                    >
                    @error('longitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="radius" class="block text-gray-700 font-semibold mb-2">
                        Radius (meter) <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="radius" 
                        name="radius" 
                        value="{{ old('radius', 10) }}"
                        class="input-field @error('radius') border-red-500 @enderror"
                        min="5"
                        max="100"
                        required
                    >
                    <p class="text-gray-500 text-xs mt-1">Default: 10 meter</p>
                    @error('radius')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-2 pt-4">
                    <a href="{{ route('locations.index') }}" class="btn-secondary flex-1 text-center">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary flex-1">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize map with Jakarta center
const map = L.map('map').setView([-6.2088, 106.8456], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

let selectedMarker = null;
let radiusCircle = null;

// Handle map click
map.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // Update form inputs
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    // Remove old marker
    if (selectedMarker) {
        map.removeLayer(selectedMarker);
    }
    if (radiusCircle) {
        map.removeLayer(radiusCircle);
    }

    // Add new marker
    selectedMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup(`<strong>Lokasi Dipilih</strong><br>Lat: ${lat.toFixed(6)}<br>Long: ${lng.toFixed(6)}`).openPopup();

    // Add radius circle
    const radius = parseInt(document.getElementById('radius').value) || 10;
    radiusCircle = L.circle([lat, lng], {
        radius: radius,
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        weight: 2
    }).addTo(map);
});

// Update circle when radius changes
document.getElementById('radius').addEventListener('change', function() {
    if (selectedMarker) {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);
        const radius = parseInt(this.value) || 10;

        if (radiusCircle) {
            map.removeLayer(radiusCircle);
        }

        radiusCircle = L.circle([lat, lng], {
            radius: radius,
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.2,
            weight: 2
        }).addTo(map);
    }
});

// Initialize with old values if exist
const oldLat = document.getElementById('latitude').value;
const oldLng = document.getElementById('longitude').value;

if (oldLat && oldLng) {
    const lat = parseFloat(oldLat);
    const lng = parseFloat(oldLng);
    const radius = parseInt(document.getElementById('radius').value) || 10;

    selectedMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup(`<strong>Lokasi Dipilih</strong><br>Lat: ${lat.toFixed(6)}<br>Long: ${lng.toFixed(6)}`).openPopup();

    radiusCircle = L.circle([lat, lng], {
        radius: radius,
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        weight: 2
    }).addTo(map);

    map.setView([lat, lng], 15);
}
</script>
@endsection
