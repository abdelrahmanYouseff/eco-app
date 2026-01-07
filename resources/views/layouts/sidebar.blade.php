<!-- [ Sidebar Menu ] start -->
<style>
  .pc-sidebar .navbar-content {
    overflow-y: auto;
    overflow-x: hidden;
    max-height: calc(100vh - 80px);
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
  }
  .pc-sidebar .navbar-content::-webkit-scrollbar {
    width: 6px;
  }
  .pc-sidebar .navbar-content::-webkit-scrollbar-track {
    background: #f7fafc;
  }
  .pc-sidebar .navbar-content::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 3px;
  }
  .pc-sidebar .navbar-content::-webkit-scrollbar-thumb:hover {
    background-color: #a0aec0;
  }
</style>
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="../dashboard/index.html" class="b-brand fw-bold text-black d-block text-center fs-4 ms-4">
          ECO Properties
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          @if(auth()->user()->role !== 'accountant')
            <li class="pc-item">
              <a href="{{ route('building.owner.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">لوحة التحكم</span>
              </a>
            </li>

            <li class="pc-item pc-caption">
              <label>إدارة الشركات</label>
              <i class="ti ti-dashboard"></i>
            </li>
            <li class="pc-item">
              <a href="{{ route('company.add') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-building"></i></span>
                <span class="pc-mtext">إضافة شركة جديدة</span>
              </a>
            </li>
            <li class="pc-item">
              <a href="{{ route('company.list') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                <span class="pc-mtext">قائمة الشركات</span>
              </a>
            </li>

            <li class="pc-item pc-caption">
              <label>المستخدمون</label>
              <i class="ti ti-news"></i>
            </li>
            <li class="pc-item">
              <a href="{{ route('user.add') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                <span class="pc-mtext">إضافة مستخدم جديد</span>
              </a>
            </li>
            <li class="pc-item">
              <a href="{{ route('user.list') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-users"></i></span>
                <span class="pc-mtext">قائمة المستخدمين</span>
              </a>
            </li>
          @endif

          <li class="pc-item pc-caption">
            <label>إدارة العقارات</label>
            <i class="ti ti-building"></i>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.buildings.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-building"></i></span>
              <span class="pc-mtext">المباني</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.units.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-layout-grid"></i></span>
              <span class="pc-mtext">الوحدات / المكاتب</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.contracts.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-file-text"></i></span>
              <span class="pc-mtext">العقود</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.tenants.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-users"></i></span>
              <span class="pc-mtext">المستأجرون / العملاء</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.payments.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-currency-dollar"></i></span>
              <span class="pc-mtext">دفعات الإيجار</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.invoices.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-file-invoice"></i></span>
              <span class="pc-mtext">الفواتير</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.receipt-vouchers.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-receipt"></i></span>
              <span class="pc-mtext">سندات القبض</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.accounting.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-report-analytics"></i></span>
              <span class="pc-mtext">المحاسبة / الكشوفات</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.brokers.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-user-check"></i></span>
              <span class="pc-mtext">الوسطاء / الوكلاء</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('property-management.settings.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-settings"></i></span>
              <span class="pc-mtext">الإعدادات</span>
            </a>
          </li>

          @if(auth()->user()->role !== 'accountant')
            <li class="pc-item pc-caption">
              <label>الخدمات</label>
              <i class="ti ti-brand-chrome"></i>
            </li>
            <li class="pc-item pc-hasmenu">
              <a href="{{ route('service.view') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-menu"></i></span><span class="pc-mtext">إضافة خدمات جديدة</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="#!">المستوى 2.1</a></li>
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link">المستوى 2.2<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="#!">المستوى 3.1</a></li>
                    <li class="pc-item"><a class="pc-link" href="#!">المستوى 3.2</a></li>
                    <li class="pc-item pc-hasmenu">
                      <a href="#!" class="pc-link">المستوى 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                      <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">المستوى 4.1</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">المستوى 4.2</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link">المستوى 2.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="#!">المستوى 3.1</a></li>
                    <li class="pc-item"><a class="pc-link" href="#!">المستوى 3.2</a></li>
                    <li class="pc-item pc-hasmenu">
                      <a href="#!" class="pc-link">المستوى 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                      <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">المستوى 4.1</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">المستوى 4.2</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            @if(Route::has('request.view'))
            <li class="pc-item">
              <a href="{{ route('request.view') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                <span class="pc-mtext">الطلبات</span>
              </a>
            </li>
            @endif

            <li class="pc-item pc-caption">
              <label>الأحداث</label>
              <i class="ti ti-news"></i>
            </li>
            <li class="pc-item">
              <a href="{{ route('event.view') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-calendar-plus"></i></span>
                <span class="pc-mtext">إضافة أحداث</span>
              </a>
            </li>

            <li class="pc-item pc-caption">
              <label>الإعدادات</label>
              <i class="ti ti-news"></i>
            </li>
            <li class="pc-item">
              <a href="../pages/login.html" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                <span class="pc-mtext">الإعدادات العامة</span>
              </a>
            </li>
          @endif

        </ul>
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end -->
