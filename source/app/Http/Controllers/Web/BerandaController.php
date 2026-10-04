<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{User,Perusahaan};
use App\Models\DeveloperUpdateLog;
use App\Models\Masterdata\MemberMCU;
use App\Models\Transaksi\Transaksi;
use App\Models\Laboratorium\{Transaksi as TransaksiLab};
use Illuminate\Http\Request;
use App\Models\Masterdata\DaftarBank;

class BerandaController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function index(Request $request){
        $user = $request->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true);
        $judul = 'Beranda Aplikasi Arta Medica Centre';
        if (!empty($perusahaan)) {
            $judul = 'Beranda Partner Artha Medica Centre';
        }
        $data = $this->getData($request, $judul , [
            'Dashboard' => route('admin.beranda'),
        ]);
        if (!empty($perusahaan)) {
            $ids = array_column($perusahaan, 'id');
            $tablePrefix = config('database.connections.mysql.prefix');
            $data['jumlah_member_terdaftar'] = MemberMCU::join('mcu_transaksi_peserta', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->whereIn('mcu_transaksi_peserta.perusahaan_id', $ids)
                ->groupBy('users_member.id')
                ->count();
            $data['jumlah_transaksi'] = Transaksi::whereIn('perusahaan_id', $ids)->count();
            $data['jumlah_rekanan'] = Transaksi::where('status_peserta','=','selesai')->whereIn('perusahaan_id', $ids)->count();
            $data['jumlah_tindakan_selesai'] = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->whereIn('mcu_transaksi_peserta.perusahaan_id', $ids)
                ->sum('transaksi.total_transaksi');
            $data['jumlah_tindakan_proses'] = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->whereIn('mcu_transaksi_peserta.perusahaan_id', $ids)
                ->where('transaksi.status_pembayaran', 'process')
                ->sum('transaksi.total_transaksi');
            return view('paneladmin.beranda.main_konten_perusahaan', ['data' => $data]);    
        }
        $data['jumlah_member_terdaftar'] = MemberMCU::count();
        $data['jumlah_transaksi'] = Transaksi::count();
        $data['jumlah_rekanan'] = Perusahaan::count();
        $data['jumlah_tindakan_selesai'] = Transaksi::where('status_peserta', 'selesai')->count();
        $data['jumlah_tindakan_proses'] = Transaksi::where('status_peserta', 'proses')->count();
        $data['updateLogs'] = DeveloperUpdateLog::where('visibility', 'internal')
            ->orderByDesc('released_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();
        return view('paneladmin.beranda.main_konten', ['data' => $data]);
    }
    public function kasir(Request $request){
        $data = $this->getData($request, 'Kasir MCU Arta Medica Centre', [
            'Kasir' => route('admin.kasir'),
        ]);
        $data['bank'] = DaftarBank::all();
        return view('paneladmin.beranda.kasir', ['data' => $data]);
    }
    public function antrian(Request $request){
        $data = $this->getData($request, 'Antrian Pasien Di Ruangan', [
            'Antrian Pasien' => route('admin.antrian'),
        ]);
        return view('paneladmin.beranda.antrian', ['data' => $data]);
    }
}
