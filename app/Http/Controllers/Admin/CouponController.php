<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:255','unique:coupons,code'],
            'discount_type' => ['required','in:percentage,fixed'],
            'discount_value' => ['required','numeric','min:0'],
            'expires_at' => ['required','date'],
            'usage_limit' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],
        ]);
        Coupon::create([
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'expires_at' => $validated['expires_at'],
            'usage_limit' => $validated['usage_limit'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('admin.settings.index', ['tab' => 'coupons'])->with('success','Kupon berhasil dibuat');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:255','unique:coupons,code,'.$coupon->id],
            'discount_type' => ['required','in:percentage,fixed'],
            'discount_value' => ['required','numeric','min:0'],
            'expires_at' => ['required','date'],
            'usage_limit' => ['nullable','integer','min:0'],
            'is_active' => ['nullable'],
        ]);
        $coupon->update([
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'expires_at' => $validated['expires_at'],
            'usage_limit' => $validated['usage_limit'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('admin.settings.index', ['tab' => 'coupons'])->with('success','Kupon berhasil diperbarui');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.settings.index', ['tab' => 'coupons'])->with('success','Kupon berhasil dihapus');
    }
}