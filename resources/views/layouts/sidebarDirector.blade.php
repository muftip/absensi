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
                <li class="nav-small-cap mt-0">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Absensi</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('absensi.masuk') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-transfer-in"></i>
                        </span>
                        <span class="hide-menu">Masuk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('absensi.keluar') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-transfer-out"></i>
                        </span>
                        <span class="hide-menu">Keluar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('absensi.izin') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-license-off"></i>
                        </span>
                        <span class="hide-menu">Izin</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('revisi') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-clock-edit"></i>
                        </span>
                        <span class="hide-menu">Revisi</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Beranda</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link
                    {{ Route::is('laporans.*') ? 'active' : '' }}"
                        href="{{ url('laporan') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-brand-google-analytics"></i>
                        </span>
                        <span class="hide-menu">Laporan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link
                    {{ Route::is('karyawan.*') ? 'active' : '' }}"
                        href="{{ route('karyawan.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Karyawan</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link
                    {{ Route::is('jabatan.*') ? 'active' : '' }}"
                        href="{{ url('jabatan') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-affiliate"></i>
                        </span>
                        <span class="hide-menu">Jabatan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link
                    {{ Route::is('hari-libur.*') ? 'active' : '' }}"
                        href="{{ url('hari-libur') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-event"></i>
                        </span>
                        <span class="hide-menu">Hari Libur</span>
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

                <li class="sidebar-item">
                    <a class="sidebar-link
                    {{ Route::is('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-key"></i>
                        </span>
                        <span class="hide-menu">Hak Akses</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link"
                        href="{{ route('riwayat') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-fingerprint"></i>
                        </span>
                        <span class="hide-menu">Riwayat</span>
                    </a>
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
