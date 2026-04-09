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
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
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
            <a href="/"
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
        </nav>

        <div class="p-6 border-t border-slate-100 text-center">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Desarrollado por Calde</p>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <header
            class="h-20 bg-white/80 glass border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-8">
                <form action="/" method="GET"
                    class="flex items-center gap-3 bg-slate-100 px-4 py-1.5 rounded-2xl border border-slate-200">
                    <i class="fas fa-user-secret text-indigo-500 text-sm"></i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Simular
                        Contexto:</span>
                    <select name="simular_usuario" onchange="this.form.submit()"
                        class="bg-transparent border-none text-xs font-bold text-slate-700 focus:ring-0 cursor-pointer">
                        @foreach($usuarios_demo as $u)
                            <option value="{{ $u->id }}" {{ session('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nombre }} ({{ $u->permission_id == 1 ? 'Admin' : 'Operador' }})
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-800">{{ $stats['usuario_nombre'] }}</p>
                    <p class="text-[10px] text-indigo-600 font-black uppercase tracking-widest">
                        {{ $stats['rol_actual'] }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($stats['usuario_nombre']) }}&background=4f46e5&color=fff"
                    class="h-10 w-10 rounded-xl border-2 border-white shadow-md">
            </div>
        </header>

        <div class="p-8 overflow-y-auto">

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Gestión de Incidentes</h2>
                    <p class="text-slate-500 mt-1">Monitoreo en tiempo real de la seguridad operativa.</p>
                </div>
                <div class="flex gap-3">
                    <a href="/api/incidentes/exportar"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-emerald-100 flex items-center gap-2 transition-all hover:-translate-y-0.5">
                        <i class="fas fa-file-excel text-lg"></i> Descargar Reporte Excel
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mb-8">
                <form action="/" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Filtrar
                            por Ubicación</label>
                        <select name="planta"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">Todas las plantas</option>
                            @foreach($plantas as $p)
                                <option value="{{ $p->id }}" {{ request('planta') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Desde
                            la Fecha</label>
                        <input type="date" name="desde" value="{{ request('desde') }}"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-600">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Hasta
                            la Fecha</label>
                        <input type="date" name="hasta" value="{{ request('hasta') }}"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-600">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-slate-800 text-white font-bold py-2.5 rounded-xl hover:bg-black transition-all">
                            <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                        </button>
                        <a href="/"
                            class="px-4 py-2.5 bg-slate-100 text-slate-400 rounded-xl hover:bg-slate-200 transition-all">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute right-[-10px] top-[-10px] text-6xl text-slate-50 opacity-50 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Registros Visibles</p>
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
                                                class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                                {{ substr($inc->empleado, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 leading-tight">
                                                    {{ $inc->empleado }}</p>
                                                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter">
                                                    {{ $inc->especialidad ?? 'Personal General' }}
                                                </p>
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
                                            {{ date('d M', strtotime($inc->fecha_incidente)) }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ date('H:i', strtotime($inc->fecha_incidente)) }} hrs
                                        </p>
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