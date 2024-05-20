@extends('layouts.user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    section{
        position: relative;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(39, 113, 0, .5), rgba(255, 245, 0, .5)); /* Gradient background dengan opasitas 0.3 */
        overflow: hidden;
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

    .btn-facebook {
        background: #3b66c4;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-facebook:hover {
        background: #3b66c4;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-google {
        background: #cf4332;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-google:hover {
        background: #cf4332;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }
    .background-img {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1; /* Meletakkan gambar di belakang konten */
        opacity: 1; /* Opacity gambar */
        background-image: url(../images/bg-1.png);
        background-size: cover; /* Mengupscalling gambar */
        background-repeat: no-repeat; /* Menghindari pengulangan gambar */
        background-position: center; 
    }

    .footer {
    background: #123300;
    color: white;
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
<section>
    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <div class="container">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <div class="row align-items-center">
                            <div class="col-auto pr-0">
                                <img src="{{ asset('images/logo-v2.png') }}" alt="Logo" class="img-fluid" style="max-height: 50px; margin-right:10px;">
                            </div>
                            <div class="col pl-0">
                                <h4 class="semi-bold mb-0 text-white">PEKAT</h4>
                                <p class="italic mt-0 mb-0 text-white">Pengaduan Masyarakat</p>
                            </div>
                        </div>
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
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    
        <div class="text-center">
            <h2 class="medium text-white mt-3">Hai, {{ Auth::guard('masyarakat')->user()->nama }}</h2>
            <p class="italic text-white mb-3">Sampaikan laporan Anda langsung kepada pemerintah yang berwenang</p>
        </div>
    </div>
{{-- 
    <div class="wave wave1"></div>
    <div class="wave wave2"></div>
    <div class="wave wave3"></div>
    <div class="wave wave4"></div> --}}
    <div class="row justify-content-center">
        <div class="col-lg-6 col-10">
            <div class="contents shadow">
    
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                @endif
    
                @if (Session::has('pengaduan'))
                <div class="alert alert-{{ Session::get('type') }}">{{ Session::get('pengaduan') }}</div>
                @endif
    
                <div class="card mb-3">Tulis Laporan Disini</div>
                <form action="{{ route('pekat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="text" value="{{ old('judul_laporan') }}" name="judul_laporan"
                            placeholder="Masukkan Judul Laporan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <textarea name="isi_laporan" placeholder="Masukkan Isi Laporan" class="form-control"
                            rows="4" required>{{ old('isi_laporan') }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" value="{{ old('tgl_kejadian') }}" name="tgl_kejadian"
                            placeholder="Pilih Tanggal Kejadian" class="form-control" onfocusin="(this.type='date')"
                            onfocusout="(this.type='text')" required>
                    </div>
                    <div class="form-group">
                        <textarea name="lokasi_kejadian" id="latlang" rows="3" class="form-control mb-3"
                            placeholder="Lokasi Kejadian" required>{{ old('lokasi_kejadian') }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <select name="kategori_kejadian" class="custom-select" id="inputGroupSelect01" required>
                                <option value="" selected>Pilih Kategori Kejadian</option>
                                <option value="agama">Agama</option>
                                <option value="hukum">Hukum</option>
                                <option value="lingkungan">Lingkungan</option>
                                <option value="sosial">Sosial</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="file" name="foto" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom mt-2">Kirim</button>
                </form>
            </div>
        </div>
    </div>
    {{-- Section Hitung Pengaduan --}}
    <div class="pengaduan mt-5">
        <div class="bg-purple">
            <div class="text-center">
                <h5 class="medium text-white mt-3">JUMLAH LAPORAN SEKARANG</h5>
                <h2 class="medium text-white">{{ $pengaduan }}</h2>
            </div>
        </div>
    </div>
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
</section>
<div class="background-img"></div>
{{-- Section Card Pengaduan --}}
{{-- Footer --}}
{{-- Modal --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mt-3">Masuk terlebih dahulu</h3>
                <p>Silahkan masuk menggunakan akun yang sudah didaftarkan.</p>
                <label>Gunakan Akun Media Sosial Anda</label>
                <div class="row">
                    <div class="col">
                        <a href="{{ route('pekat.auth', 'facebook') }}" class="btn btn-facebook mb-2"><i
                                class="fa fa-facebook" style="font-size:14px"></i> FACEBOOK</a>
                    </div>
                    <div class="col">
                        <a href="{{ route('pekat.auth', 'google') }}" class="btn btn-google"><i class="fa fa-google"
                                style="font-size:14px"></i> GOOGLE</a>
                    </div>
                </div>
                <div class="text-center">
                    <p class="my-2 text-secondary">Atau</p>
                </div>
                <form action="{{ route('pekat.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username atau Email</label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-purple text-white mt-3" style="width: 100%">MASUK</button>
                </form>
                @if (Session::has('pesan'))
                <div class="alert alert-danger mt-2">
                    {{ Session::get('pesan') }}
                </div>
                @endif
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
