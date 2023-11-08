<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="">
                    <a class="{{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard"><img src="{{ asset('assets/dashboard/img/icons/dashboard.svg') }}" alt="img"><span>
                            Dashboard</span> </a>
                </li>

                {{-- siswa --}}
                <li class="submenu">
                    <a href="javascript:void(0);" class="menu-link">
                        <img src="{{ asset('assets/dashboard/img/icons/product.svg') }}" alt="img">
                        @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                        <span>Data Siswa</span>
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
                        @if ($dataUserAuth[1] == 'admin' || $dataUserAuth[1] == 'super_admin')
                        <li>
                            <a href="{{ route('ppdb.year') }}" class="{{ Request::is('data-ppdb*') ? 'active' : '' }}">Ppdb</a>
                        </li>

                        <li>
                            <a href="{{ route('siswa.status') }}" class="{{ Request::is('data-siswa*') ? 'active' : '' }}">Siswa</a>
                        </li>

                        <li>
                            <a href="{{ route('data.absen') }}" class="{{ Request::is('data-absen/siswa*') ? 'active' : '' }}">Absen</a>
                        </li>
                        @endif

                        @if ($dataUserAuth[1] == 'siswa' || $dataUserAuth[1] == 'guru')
                        <li>
                            <a href="/absen" class="{{ Request::is('absen*') ? 'active' : '' }}">Absen</a>
                        </li>

                        <li>
                            <a href="/laporan-absen" class="{{ Request::is('laporan-absen*') ? 'active' : '' }}">Laporan Absen</a>
                        </li>
                        @endif

                        @if ($dataUserAuth[1] == 'siswa')
                        <li>
                            <a href="/data-siswa/status/aktif/kelas/{{ $dataUserAuth[0]->siswa->kelas }}/{{ $dataUserAuth[0]->siswa->uuid }}" class="{{ Request::is('data-siswa*') ? 'active' : '' }}">Biodata</a>
                        </li>
                        @endif

                        @if ($dataUserAuth[1] == 'guru')
                        <li>
                            <a href="/data-guru/{{ $dataUserAuth[0]->guru->uuid }}" class="{{ Request::is('data-guru*') ? 'active' : '' }}">Biodata</a>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- guru --}}
                @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                <li class="submenu">
                    <a href="javascript:void(0);" class="menu-link">
                        <img src="{{ asset('assets/dashboard/img/icons/product.svg') }}" alt="img">
                        @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                        <span>Data Guru</span>
                        @endif

                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                        <li>
                            <a href="{{ route('data.guru') }}" class="{{ Request::is('data-guru*') ? 'active' : '' }}">Guru</a>
                        </li>

                        <li>
                            <a href="{{ route('data.absen.guru') }}" class="{{ Request::is('data-absen/guru*') ? 'active' : '' }}">Absen</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- mata pelajaran --}}
                @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                <li class="submenu">
                    <a href="javascript:void(0);" class="menu-link">
                        <img src="{{ asset('assets/dashboard/img/icons/product.svg') }}" alt="img">
                        @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                        <span>Mata Pelajaran</span>
                        @endif

                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        @if (in_array($dataUserAuth[1], ['super_admin', 'admin']))
                        <li>
                            <a href="{{ route('data.mata.pelajaran') }}" class="{{ Request::is('data-mata-pelajaran*') ? 'active' : '' }}">Daftar Mata Pelajaran</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
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
