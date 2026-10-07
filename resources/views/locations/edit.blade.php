@extends('layouts.app')

@section('title', 'Edit Lokasi')
@section('page-title', 'Edit Lokasi - ' . $location->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Map Section -->
    <div class="lg:col-span-2">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Lokasi di Map</h3>
            <div id="map" class="w-full h-96 rounded-lg border border-gray-200"></div>
            <p class="text-gray-600 text-sm mt-4">
                <strong>Instruksi:</strong> Klik di peta untuk mengubah titik lokasi gedung unit. 
                Koordinat akan otomatis terisi di form di sebelah kanan.
            </p>
        </div>
    </div>

    <!-- Form Section -->
    <div>
        <div class="card">
            <form action="{{ route('locations.update', $location) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

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
                            <option value="{{ $unit->id }}" {{ old('unit_id', $location->unit_id) == $unit->id ? 'selected' : '' }}>
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
                        value="{{ old('name', $location->name) }}"
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
                        value="{{ old('latitude', $location->latitude) }}"
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
                        value="{{ old('longitude', $location->longitude) }}"
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
                        value="{{ old('radius', $location->radius) }}"
                        class="input-field @error('radius') border-red-500 @enderror"
                        min="5"
                        max="100"
                        required
                    >
                    @error('radius')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-gray-700 font-semibold mb-2">
                        Status
                    </label>
                    <select 
                        id="is_active" 
                        name="is_active" 
                        class="input-field"
                    >
                        <option value="1" {{ old('is_active', $location->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $location->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
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
// Initialize map
const map = L.map('map').setView([{{ $location->latitude }}, {{ $location->longitude }}], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

let selectedMarker = null;
let radiusCircle = null;

// Initialize with location data
function initializeLocation() {
    const lat = {{ $location->latitude }};
    const lng = {{ $location->longitude }};
    const radius = {{ $location->radius }};

    selectedMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup(`<strong>{{ $location->name }}</strong><br>Lat: ${lat.toFixed(6)}<br>Long: ${lng.toFixed(6)}`).openPopup();

    radiusCircle = L.circle([lat, lng], {
        radius: radius,
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.2,
        weight: 2
    }).addTo(map);
}

// Handle map click
map.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // Update form inputs
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    // Remove old marker and circle
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
    }).addTo(map).bindPopup(`<strong>Lokasi Baru</strong><br>Lat: ${lat.toFixed(6)}<br>Long: ${lng.toFixed(6)}`).openPopup();

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

// Initialize
initializeLocation();
</script>
@endsection
