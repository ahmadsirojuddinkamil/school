<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="">
                    <a class="{{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard"><img src="{{ asset('assets/dashboard/img/icons/dashboard.svg') }}" alt="img"><span>
                            Dashboard</span> </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);" class="menu-link">
                        <img src="{{ asset('assets/dashboard/img/icons/product.svg') }}" alt="img">
                        @if ($dataUserAuth[1] == 'admin')
                        <span>Management Siswa</span>
                        @endif

                        @if ($dataUserAuth[1] == 'siswa')
                        <span>Data Siswa</span>
                        @endif

                        @if ($dataUserAuth[1] == 'guru')
                        <span>Data Guru</span>
                        @endif

                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        @if ($dataUserAuth[1] == 'admin')
                        <li>
                            <a href="{{ route('ppdb.year') }}" class="{{ Request::is('ppdb-data/*') ? 'active' : '' }}">Ppdb</a>
                        </li>

                        <li>
                            <a href="{{ route('siswa.status') }}" class="{{ Request::is('siswa-data/*') ? 'active' : '' }}">Siswa</a>
                        </li>

                        <li>
                            <a href="{{ route('data.absen') }}" class="{{ Request::is('absen-data*') ? 'active' : '' }}">Absen</a>
                        </li>
                        @endif

                        @if ($dataUserAuth[1] == 'siswa' || $dataUserAuth[1] == 'guru')
                        <li><a href="/absen" class="{{ Request::is('absen*') ? 'active' : '' }}">Absen</a></li>
                        <li><a href="/laporan-absen" class="{{ Request::is('laporan-absen*') ? 'active' : '' }}">Laporan Absen</a>
                        </li>
                        @endif

                        {{-- <li><a href="/addproduct">Add Product</a></li>
                        <li><a href="/categorylist">Category List</a></li>
                        <li><a href="/addcategory">Add Category</a></li>
                        <li><a href="/subcategorylist">Sub Category List</a></li>
                        <li><a href="/subaddcategory">Add Sub Category</a></li>
                        <li><a href="/brandlist">Brand List</a></li>
                        <li><a href="/addbrand">Add Brand</a></li>
                        <li><a href="/importproduct">Import Products</a></li>
                        <li><a href="/barcode">Print Barcode</a></li> --}}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var submenuItems = $('#management-siswa ul li a');

        submenuItems.on('click', function() {
            submenuItems.removeClass('active');
            $(this).addClass('active');
            $('#management-siswa a.menu-link').addClass('active');
        });
    });

</script>
