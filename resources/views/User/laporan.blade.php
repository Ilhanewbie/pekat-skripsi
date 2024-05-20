@extends('layouts.user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
<style>
    .header {
        position: relative;
        width: 100%;
        height: 35vh;
        background: #277100;
        overflow: hidden;
    }

    .background-img{
        position: relative;
        width: 100%;
        height: 35vh;
        background-image: url(../images/bg-1.png);
        background-size: cover;
        overflow: hidden;
        z-index: 1;
    }

    .background-img::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(39, 113, 0, 0.7), rgba(255, 245, 0, 0.7));
    }

    .notification {
        padding: 14px;
        text-align: center;
        background: #f4b704;
        color: #fff;
        font-weight: 300;
    }

    .btn-white {
        background: #fff;
        color: #000;
        text-transform: uppercase;
        padding: 0px 25px 0px 25px;
        font-size: 14px;
    }

    .footer {
        background: #123300;
        color: white;
        height: 100%;
    }

    .footer .links ul {
        list-style-type: none;
    }

    .footer .links ul li a {
        color: white;
        transition: color 0.2s;
    }

    .footer .links ul li a:hover {
        text-decoration: none;
        color: #fff500;
    }

    .footer .about-company i {
    font-size: 25px;
    }

    .footer .about-company a {
        color: white;
        transition: color 0.2s;
    }

    .footer .about-company a:hover {
        color: #fff500;
    }

    .footer .location i {
        font-size: 18px;
    }

    .footer .copyright p {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

</style>
@endsection

@section('title', 'PEKAT - Pengaduan Masyarakat')

@section('content')
{{-- Section Header --}}
<section class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('pekat.index') }}">
                    <p class="italic mt-2 text-white"><i class="fas fa-chevron-left"></i> Kembali</p>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    @if(Auth::guard('masyarakat')->check())
                    <ul class="navbar-nav text-center ml-auto">
                        <li class="nav-item">
                            <a class="nav-link ml-3 text-white" href="{{ route('pekat.laporan') }}">Laporan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ml-3 text-white" href="{{ route('pekat.logout') }}"
                                style="text-decoration: underline">Logout</a>
                        </li>
                    </ul>
                    @else
                    <ul class="navbar-nav text-center ml-auto">
                        <li class="nav-item">
                            <button class="btn text-white" type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#loginModal">Masuk</button>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pekat.formRegister') }}" class="btn btn-outline-purple">Daftar</a>
                        </li>
                    </ul>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</section>
{{-- Section Card --}}
<div class="container">
    <div class="row justify-content-between">
        <div class="col-lg-12" style="height: 50%;">
            <div class="content content-bottom shadow banner-top">
                <div>
                    <img src="{{ asset('images/user_default.svg') }}" alt="user profile" class="photo">
                    <div class="self-align">
                        <h5><a style="color: #277100" href="#">{{ Auth::guard('masyarakat')->user()->nama }}</a></h5>
                        <p class="text-dark">{{ Auth::guard('masyarakat')->user()->username }}</p>
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <p class="italic mb-0">Terverifikasi</p>
                            <div class="text-center">
                                {{ $hitung[0] }}
                            </div>
                        </div>
                        <div class="col">
                            <p class="italic mb-0">Proses</p>
                            <div class="text-center">
                                {{ $hitung[1] }}
                            </div>
                        </div>
                        <div class="col">
                            <p class="italic mb-0">Selesai</p>
                            <div class="text-center">
                                {{ $hitung[2] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <a class="d-inline tab {{ $siapa != 'me' ? 'tab-active' : ''}} mr-4" href="{{ route('pekat.laporan') }}">
                Semua
            </a>
            <a class="d-inline tab {{ $siapa == 'me' ? 'tab-active' : ''}}" href="{{ route('pekat.laporan', 'me') }}">
                Laporan Saya
            </a>
            <hr>
        </div>
    </div>
    <div class="row">
        @foreach ($pengaduan as $k => $v)
        <div class="col">
            <div class="laporan-top">
                <img src="{{ asset('images/user_default.svg') }}" alt="profile" class="profile">
                <div class="d-flex justify-content-between">
                    <div>
                        <p>{{ $v->user->nama }}</p>
                        @if ($v->status == '0')
                        <p class="text-danger">Pending</p>
                        @elseif($v->status == 'proses')
                        <p class="text-warning">{{ ucwords($v->status) }}</p>
                        @else
                        <p class="text-success">{{ ucwords($v->status) }}</p>
                        @endif
                    </div>
                    <div>
                        <p>{{ $v->tgl_pengaduan->format('d M, h:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="laporan-mid">
                <div class="judul-laporan">
                    {{ $v->judul_laporan }}
                </div>
                <p>{{ $v->isi_laporan }}</p>
            </div>
            <div class="laporan-bottom">
                @if ($v->foto != null)
                <img src="{{ asset($v->foto) }}" alt="{{ 'Gambar '.$v->judul_laporan }}" class="gambar-lampiran">
                @endif
                @if ($v->tanggapan != null)
                <p class="mt-3 mb-1">{{ '*Tanggapan dari '. $v->tanggapan->petugas->nama_petugas }}</p>
                <p class="light">{{ $v->tanggapan->tanggapan }}</p>
                @endif
            </div>
            <hr>
        </div>
        @endforeach
    </div>
</div>
</div>
{{-- Footer --}}
<div class="mt-5 pt-5 pb-5 footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-xs-12 about-company">
          <h2>Tentang</h2>
          <p class="pr-5 text-white-50">PEKAT merupakan sebuah platform untuk mempermudah masyarakat dalam menyuarakan aspirasi/mengirimkan laporan pengaduan kepada pihak desa.</p>
        </div>
        <div class="col-lg-6 col-xs-12 location">
          <h4 class="mt-lg-0 mt-sm-4">Lokasi</h4>
          <p>Jl. Raya Narogong No.Km.16, RW.5, Limus Nunggal, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820, Indonesia</p>
          <p class="mb-0"><i class="fa fa-phone mr-3"></i>(021) 22950440</p>
          <p class="mb-0"><i class="fab fa-whatsapp-square mr-3"></i>08961327350 (Syahrul)</p>
          <p class="mb-0"><i class="fab fa-whatsapp-square mr-3"></i>089670406110 (Ahmad)</p>

        </div>
      </div>
      <div class="row mt-5">
        <div class="col copyright">
          <p class=""><small class="text-white-50">© 2024. Ilhan Firaldi - Universitas Singaperbangsa Karawang.</small></p>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
@if (Session::has('pesan'))
<script>
    $('#loginModal').modal('show');

</script>
@endif
@endsection
