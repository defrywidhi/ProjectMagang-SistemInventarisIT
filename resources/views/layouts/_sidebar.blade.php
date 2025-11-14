<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Sistem Inventory</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">

                {{-- Menu Dashboard (Bisa dilihat semua role) --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Menu Master Data (HANYA Admin) --}}
                @role('admin')
                <li class="nav-item {{ request()->is('kategori*') || request()->is('supplier*') || request()->is('barang*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('kategori*') || request()->is('supplier*') || request()->is('barang*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                {{-- Menu Transaksi (Admin, Teknisi) --}}
                @role('admin|teknisi')
                <li class="nav-item {{ request()->is('transaksi-masuk*') || request()->is('transaksi-keluar*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('transaksi-masuk*') || request()->is('transaksi-keluar*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-arrow-down-up"></i>
                        <p>
                            Transaksi
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Barang Masuk HANYA Admin --}}
                        @role('admin')
                        <li class="nav-item">
                            <a href="{{ route('transaksi-masuk.index') }}" class="nav-link {{ request()->is('transaksi-masuk*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang Masuk</p>
                            </a>
                        </li>
                        @endrole

                        {{-- Barang Keluar (Admin atau Teknisi) --}}
                        @role('admin|teknisi')
                        <li class="nav-item">
                            <a href="{{ route('transaksi-keluar.index') }}" class="nav-link {{ request()->is('transaksi-keluar*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang Keluar</p>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endrole

                {{-- Menu RAB (Admin atau Manajer) --}}
                @role('admin|manager')
                <li class="nav-item {{ request()->is('rab*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('rab*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-check-fill"></i>
                        <p>
                            RAB
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rab.index') }}" class="nav-link {{ request()->is('rab*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Daftar RAB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                {{-- Menu Stok Opname (Admin atau Auditor) --}}
                @role('admin|auditor')
                <li class="nav-item">
                    <a href="{{ route('stok-opname.index') }}" class="nav-link {{ request()->is('stok-opname*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard2-check-fill"></i>
                        <p>Stok Opname</p>
                    </a>
                </li>
                @endrole

                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                @endrole

            </ul>
        </nav>
    </div>
</aside>