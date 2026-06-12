<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BarterPlace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b border-gray-100 p-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="font-bold text-xl text-green-600 tracking-tight">BarterPlace.</a>

            <div class="flex gap-6 items-center">
                <a href="{{ route('home') }}"
                    class="text-gray-600 hover:text-green-600 font-semibold text-sm transition">Explore</a>

                @auth
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="text-gray-600 hover:text-green-600 font-semibold text-sm transition">Dashboard</a>
                @else
                <a href="{{ route('cart.index') }}"
                    class="text-gray-600 hover:text-green-600 font-semibold text-sm transition flex items-center gap-1">
                    🛒 Cart
                    <span class="text-[10px] bg-black text-white px-1.5 py-0.5 rounded-full font-bold">
                        {{ \App\Models\Cart::where('user_id', auth()->id())->count() }}
                    </span>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="text-gray-600 hover:text-green-600 font-semibold text-sm transition">Orders</a>
                <a href="{{ route('inventory.index') }}"
                    class="text-gray-600 hover:text-green-600 font-semibold text-sm transition">My Inventory</a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit"
                        class="text-sm font-semibold text-red-500 hover:text-red-700 cursor-pointer bg-transparent border-0 p-0">
                        Sign Out
                    </button>
                </form>

                <a href="{{ route('profile.edit') }}"
                    class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center border border-gray-200 bg-gray-200 hover:border-green-500 transition shadow-xs"
                    title="Pengaturan Profil: {{ auth()->user()->name }}">
                    @if(auth()->user()->profile_picture)
                    <img src="{{ asset('images/profiles/' . auth()->user()->profile_picture) }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-xs font-bold text-gray-600 uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                    @endif
                </a>
                @endauth

                @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900">Masuk</a>
                <a href="{{ route('register') }}"
                    class="bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-green-700 transition">
                    Daftar
                </a>
                @endguest
            </div>
        </div>
    </nav>

    @if(session('error'))
    <div id="toast-error"
        class="fixed top-20 right-6 z-50 flex items-center w-full max-w-xs p-4 text-red-800 bg-red-50 rounded-2xl shadow-lg border border-red-100 transition duration-300">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-xl">
            ⚠️
        </div>
        <div class="ms-3 text-xs font-semibold">{{ session('error') }}</div>
    </div>
    <script>
    setTimeout(() => {
        const toast = document.getElementById('toast-error');
        if (toast) {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
    </script>
    @endif

    @yield('content')

</body>

</html>