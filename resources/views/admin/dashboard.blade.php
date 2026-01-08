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
        console.error('echo not connection');
        return;
    }

    Echo.join('presence-admins')
        .here(users => {
            users.forEach(user => {
                if (user.role === 'admin') {
                    setStatus(user, 'admin', true);
                }
            });
        })
        .joining(user => {
            if (user.role === 'admin') {
                setStatus(user, 'admin', true);
            }
        })
        .leaving(user => {
            if (user.role === 'admin') {
                setStatus(user, 'admin', false);
            }
        });
    
    const customerMonitorChannel = Echo.private('customer-monitor')
        .listen('.customer.presence.changed', (event) => {

            let customerId = null;
            let status = null;
            
            if (event.customer && event.customer.id) {
                customerId = event.customer.id;
                status = event.status;
            }
                        
            if (customerId) {
                const isOnline = status === 'joined';
                updateCustomer(customerId, isOnline);
            } 
            else {
                console.log(event);
            }
        })
        .error((error) => {
            console.log('error:', error);
        })
        .subscribed(() => {
            console.log('admin subscribed to customer-monitor');
        });


    function setStatus(user, type, online) {
        const id = normalizeId(user.id);
        const dot = document.getElementById(`${type}-dot-${id}`);
        const badge = document.getElementById(`${type}-status-${id}`);

        if (!dot || !badge) {
            return;
        }

        dot.className = online
            ? 'inline-block w-3 h-3 rounded-full bg-green-500'
            : 'inline-block w-3 h-3 rounded-full bg-red-500';

        badge.className = online
            ? 'px-2 py-1 rounded text-xs bg-green-100 text-green-700'
            : 'px-2 py-1 rounded text-xs bg-red-100 text-red-700';

        badge.innerText = online ? 'Online' : 'Offline';
    }

    function updateCustomer(id, online) {

        const customerId = String(id);
        const dotId = `customer-dot-${customerId}`;
        const statusId = `customer-status-${customerId}`;
        
        
        const dot = document.getElementById(dotId);
        const status = document.getElementById(statusId);

        if (!dot || !status) {
            return;
        }

        const dotClass = online ? 'bg-green-500' : 'bg-red-500';
        const statusClass = online ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
        const statusText = online ? 'Online' : 'Offline';

        dot.className = `inline-block w-3 h-3 rounded-full ${dotClass}`;
        status.className = `px-2 py-1 rounded text-xs ${statusClass}`;
        status.innerText = statusText;
        
    }

    function normalizeId(id) {
        return typeof id === 'string' && id.includes('-')
            ? id.split('-')[1]
            : id;
    }
});
</script>
@endpush


