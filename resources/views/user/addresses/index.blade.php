@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Addresses</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300">Manage your shipping and billing addresses</p>
            </div>
            <button onclick="document.getElementById('new-address-form').classList.toggle('hidden')" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Address</button>
        </div>

        <div id="new-address-form" class="user-card p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Add New Address</h2>
            <form method="POST" action="{{ route('user.addresses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="label" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Label (e.g., Home, Work)</label>
                    <input type="text" id="label" name="label" class="w-full rounded-md text-sm" />
                </div>
                <div>
                    <label for="recipient_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Recipient Name *</label>
                    <input type="text" id="recipient_name" name="recipient_name" required class="w-full rounded-md text-sm" />
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Phone</label>
                    <input type="text" id="phone" name="phone" class="w-full rounded-md text-sm" />
                </div>
                <div>
                    <label for="line1" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Address Line 1 *</label>
                    <input type="text" id="line1" name="line1" required class="w-full rounded-md text-sm" />
                </div>
                <div>
                    <label for="line2" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Address Line 2</label>
                    <input type="text" id="line2" name="line2" class="w-full rounded-md text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="city" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">City *</label>
                        <input type="text" id="city" name="city" required class="w-full rounded-md text-sm" />
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">State/Province</label>
                        <input type="text" id="state" name="state" class="w-full rounded-md text-sm" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="postal_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" class="w-full rounded-md text-sm" />
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Country *</label>
                        <input type="text" id="country" name="country" value="US" maxlength="2" required class="w-full rounded-md text-sm" />
                    </div>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_default_shipping" value="1" class="rounded" />
                        <span>Default shipping address</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_default_billing" value="1" class="rounded" />
                        <span>Default billing address</span>
                    </label>
                </div>
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Address</button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($addresses as $address)
                <div class="user-card p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            @if($address->label)
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $address->label }}</h3>
                            @endif
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">{{ $address->recipient_name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $address->line1 }}</p>
                            @if($address->line2)
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $address->line2 }}</p>
                            @endif
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $address->country }}</p>
                            @if($address->phone)
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Phone: {{ $address->phone }}</p>
                            @endif
                            <div class="mt-2 flex gap-2">
                                @if($address->is_default_shipping)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">Default Shipping</span>
                                @endif
                                @if($address->is_default_billing)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Default Billing</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editAddress({{ $address->id }})" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Edit</button>
                            <form method="POST" action="{{ route('user.addresses.destroy', $address->id) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-500 transition hover:text-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div id="edit-form-{{ $address->id }}" class="hidden mt-4 border-t border-sf-border pt-4 dark:border-[rgba(114,138,221,0.25)]">
                        <form method="POST" action="{{ route('user.addresses.update', $address->id) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Label</label>
                                    <input type="text" name="label" value="{{ $address->label }}" class="w-full rounded-md text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Recipient Name *</label>
                                    <input type="text" name="recipient_name" value="{{ $address->recipient_name }}" required class="w-full rounded-md text-sm" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Address Line 1 *</label>
                                <input type="text" name="line1" value="{{ $address->line1 }}" required class="w-full rounded-md text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Address Line 2</label>
                                <input type="text" name="line2" value="{{ $address->line2 }}" class="w-full rounded-md text-sm" />
                            </div>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">City *</label>
                                    <input type="text" name="city" value="{{ $address->city }}" required class="w-full rounded-md text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">State</label>
                                    <input type="text" name="state" value="{{ $address->state }}" class="w-full rounded-md text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ $address->postal_code }}" class="w-full rounded-md text-sm" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Country *</label>
                                <input type="text" name="country" value="{{ $address->country }}" maxlength="2" required class="w-full rounded-md text-sm" />
                            </div>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="is_default_shipping" value="1" @checked($address->is_default_shipping) class="rounded" />
                                    <span>Default shipping</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="is_default_billing" value="1" @checked($address->is_default_billing) class="rounded" />
                                    <span>Default billing</span>
                                </label>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update</button>
                                <button type="button" onclick="document.getElementById('edit-form-{{ $address->id }}').classList.add('hidden')" class="rounded-full border border-sf-border px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-sf-surface dark:border-[rgba(114,138,221,0.35)] dark:text-slate-200">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="user-card p-6 text-center">
                    <p class="text-sm text-slate-500 dark:text-slate-300">No addresses saved yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function editAddress(id) {
    document.getElementById('edit-form-' + id).classList.toggle('hidden');
}
</script>
@endpush
@endsection
