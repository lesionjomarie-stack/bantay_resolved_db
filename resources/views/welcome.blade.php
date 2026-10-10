<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BantayResolve Portal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900 text-white antialiased">
    <div class="min-h-screen bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1600&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-slate-950/65"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-400/20 border border-amber-300/40 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-300">
                            <path fill-rule="evenodd" d="M12 1.5c-1.92 0-3.8.38-5.5 1.07a14.993 14.993 0 00-4.5 2.58C1.45 5.7 1 6.81 1 8c0 5.6 2.8 10.5 7.15 13.43a3.02 3.02 0 003.35 0C15.2 18.5 18 13.6 18 8c0-1.19-.45-2.3-1-2.85a14.993 14.993 0 00-4.5-2.58C10.8 1.88 8.92 1.5 7 1.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold tracking-tight">BantayResolve</div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/15 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-amber-300 transition">
                        Sign Up
                    </a>
                </div>
            </header>

            <main class="flex items-center justify-center min-h-[calc(100vh-90px)] py-10">
                <div class="w-full max-w-5xl rounded-[30px] border border-white/10 bg-slate-900/35 backdrop-blur-sm shadow-[0_30px_80px_rgba(15,23,42,0.45)] px-6 py-8 sm:px-10 lg:px-14">
                    <div class="flex justify-center pb-8">
                        <div class="inline-flex items-center gap-3 rounded-full border border-amber-300/50 bg-amber-300/10 px-4 py-2 text-amber-200 text-xs font-semibold uppercase tracking-[0.2em]">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                            Smart City Infrastructure & Issue Dispatch
                        </div>
                    </div>

                    <div class="text-center">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-white leading-tight">
                            BantayResolve Portal
                        </h1>
                        <p class="mx-auto mt-5 max-w-2xl text-base sm:text-lg text-slate-200/90 leading-relaxed">
                            A centralized municipal platform connecting citizens and barangay responders. Featuring satellite geolocation tracking, multi-photo evidence uploading, and real-time resolution monitoring.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-300"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.914 2.914A1 1 0 0118 7.207V19a2 2 0 01-2 2z" /></svg>
                            {{ $totalReports }} Total Reports
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-emerald-300"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $resolvedReports }} Issues Resolved
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-blue-800/40 hover:bg-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            Access Portal
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/5 px-8 py-4 text-lg font-bold text-white hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.5h-6.75A2.25 2.25 0 006 7.5v9A2.25 2.25 0 008.25 18.75h6.75A2.25 2.25 0 0017.25 16.5v-9A2.25 2.25 0 0015 5.25z" /></svg>
                            Register Citizen Account
                        </a>
                    </div>

                    <div class="mt-12 grid gap-5 md:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-5 backdrop-blur-sm">
                            <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-400/15 text-amber-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Satellite Geotagging</h3>
                            <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                                Pin exact locations and help responders reach affected areas with precision.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-5 backdrop-blur-sm">
                            <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M15 12v9h-6v-9h6z" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Real-Time Tracking</h3>
                            <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                                Monitor ongoing issue progress from report receipt to resolution in real time.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-5 backdrop-blur-sm">
                            <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-400/15 text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5m-7.5 4.5h7.5m-7.5 4.5h7.5M4.5 6.75h.01M4.5 11.25h.01M4.5 15.75h.01M19.5 6.75h.01M19.5 11.25h.01M19.5 15.75h.01" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Official Remarks & Proof</h3>
                            <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                                Review official feedback, notes, and photo evidence submitted by the city.
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>