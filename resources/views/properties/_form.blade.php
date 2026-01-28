<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Property Name</label>
    <input name="property_name" class="form-control"
           value="{{ old('property_name', $property->property_name ?? '') }}"
           placeholder="Enter property name" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug</label>
    <input name="slug" class="form-control"
           value="{{ old('slug', $property->slug ?? '') }}"
           placeholder="Auto-generated if left blank">
  </div>

  <div class="col-md-8">
    <label class="form-label">Address</label>
    <input name="address" class="form-control"
           value="{{ old('address', $property->address ?? '') }}"
           placeholder="Enter physical address">
  </div>
  <div class="col-md-4">
    <label class="form-label">Logo</label>
    <input type="file" name="logo" class="form-control" placeholder="Upload logo">
    @if(($property->logo_path ?? null))
      <img src="{{ asset('storage/'.$property->logo_path) }}" class="mt-2 rounded" style="height:48px">
    @endif
  </div>

  <div class="col-md-4">
    <label class="form-label">Contact</label>
    <input name="property_contact" class="form-control"
           value="{{ old('property_contact', $property->property_contact ?? '') }}"
           placeholder="+260 97 123 4567">
  </div>
  <div class="col-md-4">
    <label class="form-label">Email</label>
    <input name="property_email" type="email" class="form-control"
           value="{{ old('property_email', $property->property_email ?? '') }}"
           placeholder="info@property.co.zm">
  </div>

  <!-- Toggle: Add geofence / lat-lng-radius -->
  @php
    $hasGeoOld = old('lat') || old('lng') || old('radius_m');
    $hasGeoModel = isset($property) && ($property->lat || $property->lng || $property->radius_m);
    $showGeo = $hasGeoOld || $hasGeoModel;
  @endphp
  <div class="col-12 d-flex align-items-center gap-2 mt-2">
    <input id="toggle-geo" type="checkbox" class="form-check-input"
           {{ $showGeo ? 'checked' : '' }}>
    <label for="toggle-geo" class="ms-2 mb-0 fw-bold">Add location (lat, lng & radius)</label>
  </div>

  <!-- Geo block (hidden until checkbox is checked) -->
  <div id="geo-block" class="{{ $showGeo ? '' : 'd-none' }}">
    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <label class="form-label">Radius (meters)</label>
        <input name="radius_m" type="number" min="50" max="2000" class="form-control"
               value="{{ old('radius_m', $property->radius_m ?? 150) }}"
               placeholder="Default 150m">
      </div>

      <div class="col-12">
        <label class="form-label">Geofence (drag marker to pin the property)</label>
        <div id="map" style="height: 420px; border-radius: 12px;"></div>
        <div class="row mt-2">
          <div class="col-md-4">
            <label class="form-label">Latitude</label>
            <input id="lat" name="lat" class="form-control"
                   value="{{ old('lat', $property->lat ?? '') }}" placeholder="-15.xxxxxx">
          </div>
          <div class="col-md-4">
            <label class="form-label">Longitude</label>
            <input id="lng" name="lng" class="form-control"
                   value="{{ old('lng', $property->lng ?? '') }}" placeholder="28.xxxxxx">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-primary mt-2">Save</button>
  </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
  const toggle = document.getElementById('toggle-geo');
  const block  = document.getElementById('geo-block');

  let mapInited = false;
  let map, marker;

  function initMap() {
    if (mapInited) {           // already built
      setTimeout(() => map.invalidateSize(), 50);
      return;
    }
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    const startLat = Number(latInput.value || {{ (float) config('property.lat') }});
    const startLng = Number(lngInput.value || {{ (float) config('property.lng') }});

    map = L.map('map').setView([startLat, startLng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 22 }).addTo(map);

    marker = L.marker([startLat, startLng], { draggable: true })
      .addTo(map)
      .bindPopup('Drag me to property center')
      .openPopup();

    function updateInputs(latlng) {
      latInput.value = latlng.lat.toFixed(6);
      lngInput.value = latlng.lng.toFixed(6);
    }

    marker.on('dragend', (e) => updateInputs(e.target.getLatLng()));
    map.on('click', (e) => { marker.setLatLng(e.latlng); updateInputs(e.latlng); });

    mapInited = true;
    setTimeout(() => map.invalidateSize(), 50); // fix initial hidden container sizing
  }

  function showGeo() {
    block.classList.remove('d-none');
    initMap();
  }

  function hideGeo() {
    block.classList.add('d-none');
  }

  // Initial state
  if (toggle.checked) showGeo(); else hideGeo();

  // Toggle behavior
  toggle.addEventListener('change', function() {
    if (this.checked) showGeo(); else hideGeo();
  });
})();
</script>
@endpush
