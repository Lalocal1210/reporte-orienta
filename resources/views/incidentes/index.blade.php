<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orienta Portal | Security Operations Center</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }

        .sidebar-item:hover {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .sidebar-item.active {
            background: #4f46e5;
            color: white;
            shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
        }
    </style>
</head>

<body class="bg-[#f8fafc] flex min-h-screen">

    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col hidden lg:flex">
        <div class="p-6">
            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fas fa-fingerprint text-white text-xl"></i>
                </div>
                <span class="text-xl font-bold text-slate-800 tracking-tight">Orienta<span
                        class="text-indigo-600">Sync</span></span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="#"
                class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>
            <a href="#"
                class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium transition-all">
                <i class="fas fa-clipboard-list w-5"></i> Incidentes
            </a>
            <a href="#"
                class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium transition-all">
                <i class="fas fa-building w-5"></i> Plantas
            </a>
            <a href="#"
                class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium transition-all">
                <i class="fas fa-users w-5"></i> Empleados
            </a>
        </nav>

        <div class="p-6 border-t border-slate-100">
            <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">Soporte Técnico</p>
                <button class="text-indigo-600 text-sm font-bold flex items-center gap-2">
                    <i class="fas fa-headset"></i> Centro de ayuda
                </button>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <header
            class="h-20 bg-white/80 glass border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-40">
            <div class="relative w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Buscar incidentes o folios..."
                    class="w-full bg-slate-100 border-none rounded-2xl py-2.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>

            <div class="flex items-center gap-6">
                <div class="flex gap-4 border-r border-slate-200 pr-6">
                    <button class="relative text-slate-400 hover:text-indigo-600 transition-colors">
                        <i class="far fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 h-2 w-2 bg-rose-500 rounded-full"></span>
                    </button>
                    <button class="text-slate-400 hover:text-indigo-600 transition-colors">
                        <i class="far fa-comment-dots text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">Calde Dev</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">SysAdmin</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Calde+Dev&background=4f46e5&color=fff"
                        class="h-10 w-10 rounded-xl border-2 border-white shadow-md">
                </div>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Gestión de Incidentes</h2>
                    <p class="text-slate-500 mt-1">Monitoreo en tiempo real de la seguridad operativa.</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="bg-white border border-slate-200 text-slate-700 font-bold py-2.5 px-5 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                        <i class="fas fa-filter mr-2"></i> Filtros
                    </button>
                    <a href="/api/incidentes/exportar"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-100 flex items-center gap-2 transition-all hover:-translate-y-0.5">
                        <i class="fas fa-file-excel"></i> Descargar Excel
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute right-[-10px] top-[-10px] text-6xl text-slate-50 opacity-50 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Reportes</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['total']) }}</h3>
                </div>

                <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl shadow-indigo-100 relative overflow-hidden group">
                    <div
                        class="absolute right-[-10px] top-[-10px] text-6xl text-indigo-500 opacity-30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-industry"></i>
                    </div>
                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Plantas Activas</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['plantas'] }}</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute right-[-10px] top-[-10px] text-6xl text-slate-50 opacity-50 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Seguridad+</p>
                    <h3 class="text-3xl font-black text-slate-800">Activa</h3>
                </div>

                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group border-l-4 border-l-rose-500">
                    <p class="text-rose-500 text-xs font-bold uppercase tracking-widest mb-1">Alertas Sistema</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $stats['fallos'] }}</h3>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden mb-8">
                <div class="px-8 py-6 border-b border-slate-100 bg-white">
                    <h4 class="font-bold text-slate-800 text-lg">Historial de Eventos</h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-slate-100">
                                <th class="px-8 py-4">Folio</th>
                                <th class="px-8 py-4">Informante</th>
                                <th class="px-8 py-4 w-1/3">Detalle del Incidente</th>
                                <th class="px-8 py-4">Ubicación</th>
                                <th class="px-8 py-4 text-center">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($incidentes as $inc)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold">
                                            #{{ str_pad($inc->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                {{ substr($inc->empleado, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 leading-tight">
                                                    {{ $inc->empleado }}</p>
                                                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter">
                                                    {{ $inc->especialidad ?? 'General' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 italic">
                                            "{{ $inc->descripcion }}"
                                        </p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            <span class="text-sm font-semibold text-slate-600">{{ $inc->planta }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <p class="text-xs font-bold text-slate-700">
                                            {{ date('d M', strtotime($inc->fecha_incidente)) }}</p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ date('H:i', strtotime($inc->fecha_incidente)) }} hrs</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-8 border-t border-slate-50 bg-slate-50/20">
                    {{ $incidentes->links() }}
                </div>
            </div>
        </div>
    </main>

</body>

</html>