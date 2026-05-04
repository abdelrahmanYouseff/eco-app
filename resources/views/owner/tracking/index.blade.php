@extends('master')

@section('content')
  <div class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col">
              <div class="page-header-title">
                <h5 class="m-b-10">التتبع</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('building.owner.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item" aria-current="page">التتبع</li>
              </ul>
            </div>
            <div class="col-auto">
              <a href="{{ route('building.owner.dashboard') }}" class="btn btn-light">رجوع للداشبورد</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-5">
          <div class="card">
            <div class="card-header">
              <h5>المستخدمون النشطون</h5>
              <small class="text-muted">يتم التحديث تلقائيًا</small>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>المستخدم</th>
                      <th>آخر تحديث</th>
                      <th>رابط تتبع</th>
                    </tr>
                  </thead>
                  <tbody id="tracking-users-tbody">
                    <tr>
                      <td colspan="3" class="text-center py-4 text-muted">جاري التحميل...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div>
                <h5 class="mb-0">الخريطة</h5>
                <small id="tracking-selected-user" class="text-muted">اختر مستخدم من الجدول</small>
              </div>
              <div class="d-flex gap-2">
                <button id="btn-fit" type="button" class="btn btn-sm btn-outline-secondary" disabled>توسيط</button>
              </div>
            </div>
            <div class="card-body">
              <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
              <div id="tracking-map" style="height: 520px; border-radius: 12px;"></div>
              <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const selectedUserIdFromServer = @json($selectedUserId);
      const routes = {
        active: @json(route('building.owner.tracking.api.active')),
        latestBase: @json(url('/building/owner/tracking/api/users')),
        trackingPageBase: @json(route('building.owner.tracking')),
      };

      const tbody = document.getElementById('tracking-users-tbody');
      const selectedUserLabel = document.getElementById('tracking-selected-user');
      const btnFit = document.getElementById('btn-fit');

      const map = L.map('tracking-map', { zoomControl: true }).setView([30.0444, 31.2357], 6);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap',
      }).addTo(map);

      let marker = null;
      let selectedUserId = selectedUserIdFromServer;
      let selectedUserName = null;
      let activeInterval = null;
      let latestInterval = null;

      function isoToNice(iso) {
        try { return new Date(iso).toLocaleString(); } catch (e) { return iso; }
      }

      function trackingLinkFor(userId) {
        const url = new URL(routes.trackingPageBase, window.location.origin);
        url.searchParams.set('user_id', String(userId));
        return url.toString();
      }

      function setSelectedUser(userId, userName) {
        selectedUserId = userId;
        selectedUserName = userName || null;
        selectedUserLabel.textContent = selectedUserName ? ('المستخدم: ' + selectedUserName) : 'تم اختيار مستخدم';
        btnFit.disabled = false;

        if (latestInterval) window.clearInterval(latestInterval);
        latestInterval = window.setInterval(fetchLatest, 3000);
        fetchLatest();
      }

      async function fetchActive() {
        try {
          const res = await fetch(routes.active, { headers: { 'Accept': 'application/json' } });
          const json = await res.json();

          const items = (json && json.data) ? json.data : [];
          if (!Array.isArray(items) || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">لا يوجد مستخدمون نشطون الآن</td></tr>';
            return;
          }

          tbody.innerHTML = items.map((item) => {
            const link = trackingLinkFor(item.user_id);
            return `
              <tr data-user-id="${item.user_id}" data-user-name="${String(item.user_name).replaceAll('"', '&quot;')}">
                <td>
                  <a href="#!" class="tracking-select-user fw-semibold">${item.user_name}</a>
                </td>
                <td class="text-muted">${isoToNice(item.recorded_at)}</td>
                <td><a href="${link}" class="text-decoration-underline">فتح</a></td>
              </tr>
            `;
          }).join('');

          tbody.querySelectorAll('.tracking-select-user').forEach((el) => {
            el.addEventListener('click', (e) => {
              e.preventDefault();
              const tr = e.target.closest('tr');
              const userId = Number(tr.getAttribute('data-user-id'));
              const userName = tr.getAttribute('data-user-name');
              setSelectedUser(userId, userName);
              window.history.replaceState({}, '', trackingLinkFor(userId));
            });
          });

          if (selectedUserIdFromServer && !selectedUserId) {
            // no-op
          }
        } catch (e) {
          tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-danger">تعذر تحميل البيانات</td></tr>';
        }
      }

      async function fetchLatest() {
        if (!selectedUserId) return;
        try {
          const url = `${routes.latestBase}/${selectedUserId}/latest`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
          const json = await res.json();
          const d = json ? json.data : null;

          if (!d) return;
          const latlng = [d.latitude, d.longitude];
          if (!marker) {
            marker = L.marker(latlng).addTo(map);
          } else {
            marker.setLatLng(latlng);
          }
        } catch (e) {
          // ignore transient errors
        }
      }

      btnFit.addEventListener('click', () => {
        if (marker) map.setView(marker.getLatLng(), 16);
      });

      activeInterval = window.setInterval(fetchActive, 5000);
      fetchActive().then(() => {
        if (selectedUserIdFromServer) setSelectedUser(selectedUserIdFromServer, null);
      });
    })();
  </script>
@endsection

