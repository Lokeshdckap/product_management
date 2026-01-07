@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded shadow p-5">
        <h2 class="text-xl font-semibold mb-4">Admins</h2>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">State</th>
                </tr>
            </thead>
            <tbody id="admins-table">
                @foreach($admins as $admin)
                <tr id="admin-row-{{ $admin->id }}">
                    <td class="p-2">
                        <span id="admin-dot-{{ $admin->id }}"
                              class="inline-block w-3 h-3 rounded-full {{ $admin->is_online ? 'bg-green-500' : 'bg-red-500' }}">
                        </span>
                    </td>
                    <td class="p-2 font-medium">{{ auth('admin')->id() === $admin->id ? 'You' : $admin->name }}</td>
                    <td class="p-2">
                        <span id="admin-status-{{ $admin->id }}"
                              class="px-2 py-1 rounded text-xs {{ $admin->is_online ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $admin->is_online ? 'Online' : 'Offline' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded shadow p-5">
        <h2 class="text-xl font-semibold mb-4">Customers</h2>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">State</th>
                </tr>
            </thead>
            <tbody id="customers-table">
                @foreach($customers as $customer)
                <tr id="customer-row-{{ $customer->id }}">
                    <td class="p-2">
                        <span id="customer-dot-{{ $customer->id }}"
                              class="inline-block w-3 h-3 rounded-full {{ $customer->is_online ? 'bg-green-500' : 'bg-red-500' }}">
                        </span>
                    </td>
                    <td class="p-2 font-medium">{{ $customer->name }}</td>
                    <td class="p-2">
                        <span id="customer-status-{{ $customer->id }}"
                              class="px-2 py-1 rounded text-xs {{ $customer->is_online ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $customer->is_online ? 'Online' : 'Offline' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.Echo) {
        console.error('echo not connect');
        return;
    }
Echo.join('presence-admins')
    .here(users => users.forEach(u => setStatus(u, 'admin', true)))
    .joining(user => setStatus(user, 'admin', true))
    .leaving(user => setStatus(user, 'admin', false));

Echo.join('presence-customers')
    .here(users => users.forEach(u => setStatus(u, 'customer', true)))
    .joining(user => setStatus(user, 'customer', true))
    .leaving(user => setStatus(user, 'customer', false));

function setStatus(user, type, online) {
    const dot = document.getElementById(`${type}-dot-${user.id}`);
    const badge = document.getElementById(`${type}-status-${user.id}`);
    if (!dot || !badge) return;

    dot.className = online
        ? 'inline-block w-3 h-3 rounded-full bg-green-500'
        : 'inline-block w-3 h-3 rounded-full bg-red-500';

    badge.className = online
        ? 'px-2 py-1 rounded text-xs bg-green-100 text-green-700'
        : 'px-2 py-1 rounded text-xs bg-red-100 text-red-700';

    badge.innerText = online ? 'Online' : 'Offline';
}
});
</script>
@endpush
