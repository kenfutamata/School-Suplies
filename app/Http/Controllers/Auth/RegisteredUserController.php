<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:customer,seller'],
        ];

        // Additional fields for customers
        if ($request->role === 'customer') {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['shipping_address'] = ['nullable', 'string'];
        }

        // Additional fields for sellers
        if ($request->role === 'seller') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['tax_id'] = ['nullable', 'string', 'max:50'];
            $rules['contact_email'] = ['nullable', 'email'];
            $rules['contact_phone'] = ['nullable', 'string', 'max:20'];
            $rules['business_address'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone ?? null,
            'shipping_address' => $request->shipping_address ?? null,
        ]);

        // Create seller profile if seller
        if ($request->role === 'seller') {
            Seller::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'tax_id' => $request->tax_id ?? null,
                'contact_email' => $request->contact_email ?? null,
                'contact_phone' => $request->contact_phone ?? null,
                'business_address' => $request->business_address ?? null,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->getRedirectPath($user));
    }

    protected function getRedirectPath(User $user): string
    {
        return match($user->role) {
            'admin' => route('admin.dashboard'),
            'seller' => route('seller.dashboard'),
            'customer' => route('customer.dashboard'),
            default => route('dashboard'),
        };
    }
}
