<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Word Puzzles Game')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .letter-box {
            @apply inline-block w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold mx-1 mb-2 bg-white;
        }
        .letter-box.used {
            @apply bg-gray-200 text-gray-500;
        }
        .letter-box.available {
            @apply bg-blue-100 border-blue-300 text-blue-700;
        }
        .word-badge {
            @apply inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mr-2 mb-2;
        }
        .score-badge {
            @apply inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-puzzle-piece mr-2"></i>
                        Word Puzzles
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">

                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-home mr-1"></i>
                            Home
                        </a>
                    @else
                        <a href="{{ route('game.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-gamepad mr-1"></i>
                            Play
                        </a>
                    @endif
                    <a href="{{ route('leaderboard.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-trophy mr-1"></i>
                        Leaderboard
                    </a>
                    <a href="{{ route('logout') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Word Puzzles Game.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html> 