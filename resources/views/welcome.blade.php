<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bug Tracker Eva</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 relative min-h-screen flex items-center justify-center overflow-hidden selection:bg-blue-500 selection:text-white">

        <!-- Background Decoration (Glassmorphism Depth) -->
        <div class="fixed top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="fixed bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-[500px] h-[500px] bg-indigo-400/20 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Main Content -->
        <div class="relative z-10 p-6 max-w-5xl w-full text-center">
            
            <!-- Logo Icon -->
            <div class="mb-10 flex justify-center">
                <div class="h-24 w-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-blue-500/40 transform rotate-3 transition-transform duration-300 hover:rotate-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.632 6.999 12 6.999c-1.632 0-3.132 2.053-3.132 5.001 0 5.467 4.5 9 4.5 9Zm3.5-9c0 1.25-.97 2.25-2.25 2.25h6.75M12 12c-1.25 0-2.25.97-2.25 2.25H3" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-6xl md:text-8xl font-bold text-slate-900 mb-8 tracking-tight leading-none">
                Bug Tracker <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">Eva</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-2xl md:text-3xl text-slate-600 mb-14 leading-relaxed font-light max-w-4xl mx-auto">
                Central de Monitoramento e Correção de Incidentes.
            </p>

            <!-- CTA Button -->
            <a href="/login" class="group relative inline-flex items-center justify-center w-full sm:w-auto px-12 py-6 text-xl md:text-2xl font-bold text-white transition-all duration-200 bg-orange-500 rounded-2xl hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-offset-4 focus:ring-orange-500 shadow-xl shadow-orange-500/30 hover:shadow-orange-500/50 hover:-translate-y-1">
                <span>Acessar o Sistema</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
            
            <!-- Minimal Footer -->
            <div class="mt-16 text-sm font-medium text-slate-400 uppercase tracking-widest opacity-60">
                &copy; {{ date('Y') }} Bug Tracker Eva
            </div>
        </div>

    </body>
</html>
