<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between mt-3 mb-4">
            <div class="logo-img d-flex align-items-center">
                @php
                    $foto = session('foto');
                    $defaultFoto = '/assets/images/profile/user-1.jpg'; // Nama file default gambar
                @endphp

                @if ($foto && file_exists(public_path('storage/' . $foto)))
                    <img class="rounded-circle me-3" src="{{ asset('storage/' . $foto) }}" alt="" width="60"
                        height="60">
                @else
                    <img class="rounded-circle me-3" src="{{ asset($defaultFoto) }}" alt="" width="60"
                        height="60">
                @endif

                <div>
                    <div>{{ session('nama') }}</div>
                    <div class="fs-1 text-danger" style="color: dimgray;">{{ session('hak_akses') }}</div>
                </div>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Beranda</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('laporan') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-brand-google-analytics"></i>
                        </span>
                        <span class="hide-menu">Laporan</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('log-harian') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report-analytics"></i>
                        </span>
                        <span class="hide-menu">Log Harian</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Akun</span>
                </li>

                <li class="sidebar-item mb-5">
                    <a class="sidebar-link" href="{{ route('logout') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
