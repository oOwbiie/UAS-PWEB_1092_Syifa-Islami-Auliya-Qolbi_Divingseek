<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::take(3)->get();
        return view('home', compact('packages'));
    }

    public function packages()
    {
        $packages = Package::all();
        return view('packages', compact('packages'));
    }

    public function reserveForm($package_id)
    {
        $package = Package::findOrFail($package_id);
        return view('reserve', compact('package'));
    }

    public function reserveSubmit(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'nama_customer' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_hp' => 'required|string|max:20',
            'tanggal_diving' => 'required|date|after_or_equal:today',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        $package = Package::findOrFail($request->package_id);
        $total_harga = $package->harga * $request->jumlah_peserta;

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'package_id' => $request->package_id,
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'tanggal_diving' => $request->tanggal_diving,
            'jumlah_peserta' => $request->jumlah_peserta,
            'total_harga' => $total_harga,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('payment.page', $reservation->id)->with('success', 'Reservasi berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function paymentPage($reservation_id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($reservation_id);
        // If already paid or disetujui, go to detail invoice directly
        if ($reservation->status_pembayaran === 'paid' || $reservation->status_pembayaran === 'disetujui') {
            return redirect()->route('reservation.detail', $reservation->id);
        }
        return view('qris', compact('reservation'));
    }

    public function uploadBukti(Request $request, $reservation_id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($reservation_id);

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old proof if exists
            if ($reservation->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($reservation->bukti_pembayaran)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($reservation->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('payment_proofs', 'public');

            $reservation->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'menunggu_verifikasi',
                'payment_date' => now(),
            ]);

            return redirect()->route('payment.page', $reservation->id)->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    public function reservationDetail($reservation_id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($reservation_id);
        
        // Only allow paid or approved reservations to access invoice
        if ($reservation->status_pembayaran !== 'paid' && $reservation->status_pembayaran !== 'disetujui') {
            return redirect()->route('payment.page', $reservation->id);
        }
        
        return view('detail', compact('reservation'));
    }

    public function myReservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('my_reservations', compact('reservations'));
    }
}
