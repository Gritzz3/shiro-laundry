<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    /**
     * Show the landing page with tracking form.
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Search for an order by nomor_nota.
     * Optional: verify last 4 digits of phone number.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_nota' => 'required|string|max:20',
            'verifikasi_hp' => 'nullable|string|size:4',
        ]);

        $order = Order::where('nomor_nota', $request->input('nomor_nota'))
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Nota Tidak Terdaftar',
            ], 404);
        }

        // Optional phone verification
        if ($request->filled('verifikasi_hp')) {
            $lastFour = substr($order->no_hp, -4);
            if ($lastFour !== $request->input('verifikasi_hp')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi nomor HP gagal. Pastikan 4 digit terakhir benar.',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_nota'     => $order->nomor_nota,
                'nama_pelanggan' => $order->nama_pelanggan,
                'status_cucian'  => $order->status_cucian,
                'status_label'   => Order::STATUSES[$order->status_cucian] ?? $order->status_cucian,
                'status_step'    => $order->getStatusStep(),
                'total_steps'    => count(Order::STATUSES),
                'cabang'         => $order->outlet->nama_cabang ?? '-',
                'total_harga'    => number_format($order->total_harga, 0, ',', '.'),
                'updated_at'     => $order->updated_at->format('d M Y, H:i'),
            ],
        ]);
    }
}
