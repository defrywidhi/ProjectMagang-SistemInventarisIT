<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link"> {{-- Arahkan ke dashboard --}}
            <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Sistem Inventory</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">

                {{-- Menu Dashboard --}}
                <li class="nav-item">
                    {{-- Tambahkan class 'active' jika route saat ini adalah 'dashboard' --}}
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Menu Master Data (Dropdown) --}}
                {{-- Tambahkan class 'menu-open' jika URL saat ini diawali 'kategori*', 'supplier*', atau 'barang*' --}}
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
                            {{-- Tambahkan class 'active' jika URL saat ini diawali 'kategori*' --}}
                            <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Tambahkan class 'active' jika URL saat ini diawali 'supplier*' --}}
                            <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Tambahkan class 'active' jika URL saat ini diawali 'barang*' --}}
                            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang</p>
                            </a>
                        </li>
                    </ul>
                </li> {{-- Penutup </li> Master Data --}}

                {{-- Menu Transaksi (Dropdown) --}}
                {{-- Tambahkan class 'menu-open' jika URL saat ini diawali 'transaksi-masuk*' atau 'transaksi-keluar*' --}}
                <li class="nav-item {{ request()->is('transaksi-masuk*') || request()->is('transaksi-keluar*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('transaksi-masuk*') || request()->is('transaksi-keluar*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-arrow-down-up"></i>
                        <p>
                            Transaksi
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            {{-- Tambahkan class 'active' jika URL saat ini diawali 'transaksi-masuk*' --}}
                            <a href="{{ route('transaksi-masuk.index') }}" class="nav-link {{ request()->is('transaksi-masuk*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Tambahkan class 'active' jika URL saat ini diawali 'transaksi-keluar*' --}}
                            <a href="{{ route('transaksi-keluar.index') }}" class="nav-link {{ request()->is('transaksi-keluar*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Barang Keluar</p>
                            </a>
                        </li>
                    </ul>
                </li> {{-- Penutup </li> Transaksi --}}

            </ul>
        </nav>
    </div>
</aside>