<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\VerifikasiEmailUntukRegistrasiPengaduanMasyarakat;
use App\Models\Masyarakat;
use App\Models\Petugas;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // Menghitung jumlah pengaduan yang ada di table
        $pengaduan = Pengaduan::all()->count();

        // Arahkan ke file user/landing.blade.php
        return view('User.landing', ['pengaduan' => $pengaduan]);
    }

    public function landing_page(){
        if (Auth::guard('masyarakat')->check()) {
            // Jika otentikasi berhasil, arahkan ke view 'landingpage'
            return redirect()->route('pekat.index');
        } elseif(Auth::guard('admin')->check()){
            return redirect()->route('dashboard.index');
        }else {
        // Jika pengguna belum terotentikasi, arahkan ke halaman login
        return view('landingpage');
        }
    }

    public function login(Request $request)
    {
        if(Petugas::where('username', $request->username)->exists()){
            $petugas = Petugas::where('username', $request->username)->first();
            if (!$petugas) {
                return redirect()->back()->with(['pesan' => 'Username tidak terdaftar!']);
            }

            $passwordPetugas = Hash::check($request->password, $petugas->password);

            if (!$passwordPetugas) {
                return redirect()->back()->with(['pesan' => 'Password tidak sesuai!']);
            }

            $auth = Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password]);

            if ($auth) {
                return redirect()->route('dashboard.index');
            } else {
                return redirect()->back()->with(['pesan' => 'Akun tidak terdaftar!']);
            }
        }
        // Pengecekan $request->username isinya email atau username
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            // jika isinya string email, cek email nya di table masyarakat
            $email = Masyarakat::where('email', $request->username)->first();

            // Pengecekan variable $email jika tidak ada di table masyarakat
            if (!$email) {
                return redirect()->back()->with(['pesan' => 'Email tidak terdaftar']);
            }

            // jika email ada, langsung check password yang dikirim di form dan di table, hasilnya sama atau tidak
            $password = Hash::check($request->password, $email->password);

            // Pengecekan variable $password jika password tidak sama dengan yang dikirimkan
            if (!$password) {
                return redirect()->back()->with(['pesan' => 'Password tidak sesuai']);
            }

            // Jalankan fungsi auth jika berjasil melewati validasi di atas
            if (Auth::guard('masyarakat')->attempt(['email' => $request->username, 'password' => $request->password])) {
                // Jika login berhasil
                return redirect()->route('pekat.index');
            } else {
                // Jika login gagal
                return redirect()->back()->with(['pesan' => 'Akun tidak terdaftar!']);
            }
        } else {
            // jika isinya string username, cek username nya di table masyarakat
            $username = Masyarakat::where('username', $request->username)->first();

            // Pengecekan variable $username jika tidak ada di table masyarakat
            if (!$username) {
                return redirect()->back()->with(['pesan' => 'Username tidak terdaftar']);
            }

            // jika username ada, langsung check password yang dikirim di form dan di table, hasilnya sama atau tidak
            $password = Hash::check($request->password, $username->password);

            // Pengecekan variable $password jika password tidak sama dengan yang dikirimkan
            if (!$password) {
                return redirect()->back()->with(['pesan' => 'Password tidak sesuai']);
            }

            // Jalankan fungsi auth jika berjasil melewati validasi di atas
            if (Auth::guard('masyarakat')->attempt(['username' => $request->username, 'password' => $request->password])) {
                // Jika login berhasil
                return redirect()->route('pekat.index');
            } else {
                // Jika login gagal
                return redirect()->back()->with(['pesan' => 'Akun tidak terdaftar!']);
            }
        }
    }

    public function formRegister()
    {
        // Arahkan ke file user/register.blade.php
        return view('user.register');
    }

    public function register(Request $request)
    {
        // Masukkan semua data yg dikirim ke variable $data
        $data = $request->all();

        // Buat variable $validate kemudian isinya Validator::make(datanya, [nama_field => peraturannya])
        $validate = Validator::make($data, [
            'nik' => ['required', 'unique:masyarakat'],
            'nama' => ['required', 'string'],
            'email' => ['required', 'email', 'string', 'unique:masyarakat'],
            'username' => ['required', 'string', 'regex:/^\S*$/u', 'unique:masyarakat'],
            'password' => ['required', 'min:6'],
            'telp' => ['required'],
        ]);

        // Pengecekan jika validate fails atau gagal
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Mengecek email
        $email = Masyarakat::where('email', $request->username)->first();

        // Pengecekan jika email sudah terdaftar
        if ($email) {
            return redirect()->back()->with(['pesan' => 'Email sudah terdaftar'])->withInput(['email' => 'asd']);
        }

        // Mengecek username
        $username = Masyarakat::where('username', $request->username)->first();

        // Pengecekan jika username sudah terdaftar
        if ($username) {
            return redirect()->back()->with(['pesan' => 'Username sudah terdaftar'])->withInput(['username' => null]);
        }

        // Memasukkan data kedalam table Masyarakat
        Masyarakat::create([
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'telp' => $data['telp'],
        ]);

        // Kirim link verifikasi email
        // $link = URL::temporarySignedRoute('pekat.verify', now()->addMinutes(30), ['nik' => $data['nik']]);
        // Mail::to($data['email'])->send(new VerifikasiEmailUntukRegistrasiPengaduanMasyarakat($data['nama'], $link));

        // Arahkan ke route pekat.index
        // Otentikasi pengguna
        if (Auth::guard('masyarakat')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // Jika otentikasi berhasil, arahkan ke route pekat.index
            return redirect()->route('pekat.index');
        }
    }

    public function logout()
    {
        // Fungsi logout dengan guard('masyarakat')
        Auth::guard('masyarakat')->logout();

        // Arahkan ke route pekat.index
        return redirect()->route('pekat.index');
    }

    public function storePengaduan(Request $request)
    {
        // Pengecekan jika tidak ada masyarakat yang sedang login
        if (!Auth::guard('masyarakat')->user()) {
            return redirect()->back()->with(['pesan' => 'Login dibutuhkan!'])->withInput();
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'judul_laporan' => ['required'],
            'isi_laporan' => ['required'],
            'tgl_kejadian' => ['required'],
            'lokasi_kejadian' => ['required'],
            'kategori_kejadian' => ['required'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . mt_rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
            if ($file->storeAs('pengaduan', $fileName,'public')) {
                $fotoPath = 'storage/pengaduan/' . $fileName;
                $data['foto'] = $fotoPath;
            } else {
                return redirect()->back()->with(['pengaduan' => 'Gagal menyimpan foto!', 'type' => 'danger']);
            }
        }

        date_default_timezone_set('Asia/Bangkok');

        $pengaduan = Pengaduan::create([
            'tgl_pengaduan' => date('Y-m-d h:i:s'),
            'nik' => Auth::guard('masyarakat')->user()->nik,
            'id_masyarakat' => Auth::guard('masyarakat')->user()->id_masyarakat,
            'judul_laporan' => $data['judul_laporan'],
            'isi_laporan' => $data['isi_laporan'],
            'tgl_kejadian' => $data['tgl_kejadian'],
            'lokasi_kejadian' => $data['lokasi_kejadian'],
            'kategori_kejadian' => $data['kategori_kejadian'],
            'foto' => $data['foto'] ?? '',
            'status' => '0',
        ]);

        // Pengecekan variable $pengaduan
        if ($pengaduan) {
            // Jika mengirim pengaduan berhasil
            return redirect()->route('pekat.laporan', 'me')->with(['pengaduan' => 'Berhasil terkirim!', 'type' => 'success']);
        } else {
            // Jika mengirim pengaduan gagal
            return redirect()->back()->with(['pengaduan' => 'Gagal terkirim!', 'type' => 'danger']);
        }
    }

    public function laporan($siapa = '')
    {
        // Membuat variable $terverifikasi isinya menghitung pengaduan status pending
        $terverifikasi = Pengaduan::where([['id_masyarakat', Auth::guard('masyarakat')->user()->id_masyarakat], ['status', '!=', '0']])->get()->count();
        // Membuat variable $terverifikasi isinya menghitung pengaduan status proses
        $proses = Pengaduan::where([['id_masyarakat', Auth::guard('masyarakat')->user()->id_masyarakat], ['status', 'proses']])->get()->count();
        // Membuat variable $terverifikasi isinya menghitung pengaduan status selesai
        $selesai = Pengaduan::where([['id_masyarakat', Auth::guard('masyarakat')->user()->id_masyarakat], ['status', 'selesai']])->get()->count();

        // Masukkan 3 variable diatas ke dalam variable array $hitung
        $hitung = [$terverifikasi, $proses, $selesai];

        // Pengecekan jika ada parameter $siapa yang dikirimkan di url
        if ($siapa == 'me') {
            // Jika $siapa isinya 'me'
            $pengaduan = Pengaduan::where('id_masyarakat', Auth::guard('masyarakat')->user()->id_masyarakat)->orderBy('tgl_pengaduan', 'desc')->get();

            // Arahkan ke file user/laporan.blade.php sebari kirim data pengaduan, hitung, siapa
            return view('user.laporan', ['pengaduan' => $pengaduan, 'hitung' => $hitung, 'siapa' => $siapa]);
        } else {
            // Jika $siapa kosong
            $pengaduan = Pengaduan::where([['id_masyarakat', '!=', Auth::guard('masyarakat')->user()->id_masyarakat], ['status', '!=', '0']])->orderBy('tgl_pengaduan', 'desc')->get();

            // Arahkan ke file user/laporan.blade.php sebari kirim data pengaduan, hitung, siapa
            return view('user.laporan', ['pengaduan' => $pengaduan, 'hitung' => $hitung, 'siapa' => $siapa]);
        }
    }
}
