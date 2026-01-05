@extends('layouts.app')

@section('content')
<div class="space-y-6">

<h2>Online Users</h2>

<ul id="online-users"></ul>

</div>
@endsection

<script>
    const list = document.getElementById('online-users');

    Echo.join('online-users')
        .here((users) => {
            list.innerHTML = '';
            users.forEach(addUser);
        })
        .joining((user) => {
            addUser(user);
        })
        .leaving((user) => {
            document.getElementById(`user-${user.type}-${user.id}`)?.remove();
        });

    function addUser(user) {
        const li = document.createElement('li');
        li.id = `user-${user.type}-${user.id}`;
        li.innerText = `${user.name} (${user.type})`;
        list.appendChild(li);
    }
</script>
