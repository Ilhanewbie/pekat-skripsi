@extends('layouts.user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
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

</style>
@endsection

@section('title', 'PEKAT - Pengaduan Masyarakat')

@section('content')
{{-- Section Header --}}
<section class="header">
        <div class="row">
        <div class="col d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="text-center">
                <img src="{{ asset('images/logo.png') }}" width="18%" height="18%">
                <h1 class="text-white mb-0 display-4">PEKAT</h2>
                <h2 class="medium text-white">(Pengaduan Masyarakat)</h2>
                <p class="italic text-white mb-3">Sampaikan laporan Anda langsung kepada yang pemerintah berwenang</p>
                <div class="row d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-light btn-lg mr-3" data-toggle="modal" data-target="#loginModal">Masuk</button>
                    <a href="{{ route('pekat.formRegister') }}" class="btn btn-light btn-lg text-primary">Daftar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="wave wave1"></div>
    <div class="wave wave2"></div>
    <div class="wave wave3"></div>
    <div class="wave wave4"></div>
</section>
{{-- Modal --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@if (Session::has('pesan'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: '{{ Session::get('pesan') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@endsection
