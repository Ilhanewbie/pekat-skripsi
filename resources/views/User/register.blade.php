@extends('layouts.user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        position: relative;
        width: 100%;
        height: 100vh;
        background: linear-gradient(to bottom, #277100, #fff500); /* Gradient background */
        overflow: hidden;
    }

    .btn-purple {
        background: #277100;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-yellow {
        background: #fff500;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-purple:hover {
        background: #277100;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-facebook {
        background: #277100;
        width: 100%;
        color: #fff;
        font-weight: 600;
    }

    .btn-facebook:hover {
        background: #277100;
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
        opacity: 0.3; /* Opacity gambar */
        background-image: url(../images/bg-1.png);
        background-size: cover; /* Mengupscalling gambar */
        background-repeat: no-repeat; /* Menghindari pengulangan gambar */
        background-position: center; 
    }

</style>
@endsection

@section('title', 'Halaman Daftar')

@section('content')
<div class="container">
    <div class="background-img"></div>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-5 mt-5">
            <div class="row d-flex justify-content-center" width="100%">
                <img src="{{ asset('images/logo.png') }}" width="30%" height="30%" class="mb-0 mt-2">
                <img src="{{ asset('images/logo-v2.png') }}" width="20%" class="mb-0">
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h2 class="text-center mb-4">FORM DAFTAR</h2>
                    <form action="{{ route('pekat.register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" value="{{ old('nik') }}" name="nik" placeholder="NIK" class="form-control @error('nik') is-invalid @enderror" oninput="validateNik(this)">
                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" value="{{ old('nama') }}" name="nama" placeholder="Nama Lengkap" class="form-control @error('nama') is-invalid @enderror">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" value="{{ old('email') }}" name="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" value="{{ old('username') }}" name="username" placeholder="Username" class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" value="{{ old('telp') }}" name="telp" placeholder="No. Telp" class="form-control @error('telp') is-invalid @enderror" oninput="validateNohp(this)">
                            @error('telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-yellow text-dark">DAFTAR</button>
                    </form>
                </div>
            </div>
            @if (Session::has('pesan'))
                <div class="alert alert-danger my-2">
                    {{ Session::get('pesan') }}
                </div>
            @endif
            <a href="{{ route('pekat.landingPage') }}" class="btn btn-purple text-white mt-3 mb-3" style="width: 100%; font-weight: 600">Kembali ke Halaman Utama</a>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function validateNik(input) {
        input.value = input.value.replace(/\D/g, '');

        if (input.value.length > 16) {
            input.value = input.value.slice(0, 16);
        }
    }

    function validateNohp(input) {
    input.value = input.value.replace(/\D/g, '');

    if (input.value.length < 10) {
        input.classList.add('is-invalid');
    } else {
        input.classList.remove('is-invalid');
    }
}
</script>
@endsection
