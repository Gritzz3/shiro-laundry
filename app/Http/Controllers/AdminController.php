<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Show dashboard with orders list.
     */
    public function dashboard(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $query = Order::query();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_nota', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status_cucian', $status);
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        // Summary stats
        $stats = [
            'total_orders' => Order::count(),
            'antrean'      => Order::where('status_cucian', 'antrean')->count(),
            'proses'       => Order::where('status_cucian', 'proses_cuci')->count(),
            'setrika'      => Order::where('status_cucian', 'setrika_packing')->count(),
            'selesai'      => Order::where('status_cucian', 'selesai')->count(),
        ];

        return view('admin.dashboard', compact('orders', 'stats', 'user'));
    }

    /**
     * Update order status to the next step.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $nextStatus = $order->getNextStatus();

        if (!$nextStatus) {
            return back()->with('error', 'Order sudah dalam status akhir.');
        }

        $order->status_cucian = $nextStatus;
        $order->touch();
        $order->save();

        // Trigger WhatsApp notification when status changes to 'selesai'
        if ($nextStatus === 'selesai') {
            $this->sendWhatsAppNotification($order);
        }

        return back()->with('success', "Status order {$order->nomor_nota} diperbarui ke " . Order::STATUSES[$nextStatus]);
    }

    /**
     * Show form to create a new order.
     */
    public function createOrder()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('admin.create-order', compact('user'));
    }

    /**
     * Store a new order.
     */
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'total_harga'    => 'required|numeric|min:0',
        ]);

        // Generate nomor nota: SHR-0001, SHR-0002, etc.
        $lastOrder = Order::orderBy('id', 'desc')->first();

        $nextNumber = 1;
        if ($lastOrder && preg_match('/(\d+)$/', $lastOrder->nomor_nota, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $validated['nomor_nota'] = sprintf('SHR-%04d', $nextNumber);
        $validated['status_cucian'] = 'antrean';

        Order::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', "Order {$validated['nomor_nota']} berhasil dibuat.");
    }

    /**
     * User management page (Super Admin only).
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Store a new user (Super Admin only).
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'role'      => 'required|in:super_admin,kasir',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users')
            ->with('success', "User {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Delete a user (Super Admin only).
     */
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Send WhatsApp notification when order is completed.
     */
    private function sendWhatsAppNotification(Order $order): void
    {
        $gatewayUrl = config('services.whatsapp.gateway_url');
        $apiKey = config('services.whatsapp.api_key');

        if (empty($gatewayUrl) || empty($apiKey)) {
            return; // WhatsApp gateway not configured
        }

        $message = "Halo {$order->nama_pelanggan}, cucian Anda dengan Nota {$order->nomor_nota} "
            . "di Shiro Laundry telah SELESAI dan siap diambil. "
            . "Terima kasih! 🧺✨";

        try {
            Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post($gatewayUrl, [
                'target'  => $order->no_hp,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }
    }
}
