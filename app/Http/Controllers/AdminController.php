<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Reservation;
use App\Models\ContactInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalPackages = Package::count();
        $totalReservations = Reservation::count();
        $paidReservations = Reservation::whereIn('status_pembayaran', ['paid', 'disetujui'])->count();
        $totalRevenue = Reservation::whereIn('status_pembayaran', ['paid', 'disetujui'])->sum('total_harga');
        $pendingVerificationCount = Reservation::where('status_pembayaran', 'menunggu_verifikasi')->count();

        $query = Reservation::with(['package', 'user' => function($q) {
            $q->withCount('reservations');
        }]);

        // Search by customer name, email, phone, or package name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%")
                  ->orWhereHas('package', function($qp) use ($search) {
                      $qp->where('nama_paket', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalPackages',
            'totalReservations',
            'paidReservations',
            'totalRevenue',
            'pendingVerificationCount',
            'reservations'
        ));
    }

    public function packagesIndex()
    {
        $packages = Package::all();
        return view('admin.packages_index', compact('packages'));
    }

    public function packagesCreate()
    {
        return view('admin.package_form');
    }

    public function packagesStore(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'required|in:beginner,intermediate,professional',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_url' => 'nullable|string|max:255',
        ]);

        $gambar = 'default.jpg';
        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('packages', 'public');
            $gambar = $path;
        } elseif ($request->filled('gambar_url')) {
            $gambar = $request->gambar_url;
        }

        Package::create([
            'nama_paket' => $request->nama_paket,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'harga' => $request->harga,
            'durasi' => $request->durasi,
            'gambar' => $gambar,
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket diving berhasil ditambahkan!');
    }

    public function packagesEdit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.package_form', compact('package'));
    }

    public function packagesUpdate(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'required|in:beginner,intermediate,professional',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_url' => 'nullable|string|max:255',
        ]);

        $gambar = $package->gambar;
        if ($request->hasFile('gambar_file')) {
            // Delete old image if exists locally
            if ($package->gambar && Storage::disk('public')->exists($package->gambar)) {
                Storage::disk('public')->delete($package->gambar);
            }
            $path = $request->file('gambar_file')->store('packages', 'public');
            $gambar = $path;
        } elseif ($request->filled('gambar_url')) {
            if ($request->gambar_url !== $package->gambar) {
                // Delete old image if exists locally
                if ($package->gambar && Storage::disk('public')->exists($package->gambar)) {
                    Storage::disk('public')->delete($package->gambar);
                }
                $gambar = $request->gambar_url;
            }
        } elseif ($request->boolean('remove_image')) {
            if ($package->gambar && Storage::disk('public')->exists($package->gambar)) {
                Storage::disk('public')->delete($package->gambar);
            }
            $gambar = 'default.jpg';
        }

        $package->update([
            'nama_paket' => $request->nama_paket,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'harga' => $request->harga,
            'durasi' => $request->durasi,
            'gambar' => $gambar,
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket diving berhasil diperbarui!');
    }

    public function packagesDestroy($id)
    {
        $package = Package::findOrFail($id);
        if ($package->gambar && Storage::disk('public')->exists($package->gambar)) {
            Storage::disk('public')->delete($package->gambar);
        }
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Paket diving berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,cancelled,menunggu_verifikasi,disetujui,ditolak',
        ]);

        $reservation->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Status pembayaran reservasi #' . $id . ' berhasil diperbarui!');
    }

    public function verifikasiIndex()
    {
        $reservations = Reservation::with('package')
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->orderBy('payment_date', 'asc')
            ->get();
        return view('admin.verifikasi', compact('reservations'));
    }

    public function verifikasiApprove($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'status_pembayaran' => 'disetujui',
            'verified_by_admin' => auth()->id(),
            'verification_date' => now(),
        ]);
        return redirect()->back()->with('success', 'Pembayaran untuk reservasi #' . $id . ' telah DISETUJUI.');
    }

    public function verifikasiReject(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        $reservation->update([
            'status_pembayaran' => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
            'verified_by_admin' => auth()->id(),
            'verification_date' => now(),
        ]);
        return redirect()->back()->with('success', 'Pembayaran untuk reservasi #' . $id . ' telah DITOLAK.');
    }

    public function reservationsDestroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Data reservasi berhasil dihapus!');
    }

    public function contactEdit()
    {
        $contact = ContactInformation::first();
        return view('admin.contact', compact('contact'));
    }

    public function contactUpdate(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
            'nomor_hp' => 'required|string|max:20',
            'jam_buka' => 'required|string|max:100',
            'email' => 'required|email|max:100',
        ]);

        $contact = ContactInformation::first();
        if (!$contact) {
            ContactInformation::create($request->all());
        } else {
            $contact->update($request->all());
        }

        return redirect()->route('admin.dashboard')->with('success', 'Informasi kontak kantor diving berhasil diperbarui!');
    }
}
