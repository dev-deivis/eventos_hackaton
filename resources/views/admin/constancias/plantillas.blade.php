<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Generador de Constancias</h1>
                        <p class="text-gray-600 mt-1">Crear y gestionar certificados digitales verificables</p>
                    </div>
                </div>
            </div>

            <!-- Tabs de Navegación -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <a href="{{ route('admin.constancias.index') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Constancias Emitidas
                    </a>
                    <a href="{{ route('admin.constancias.plantillas') }}" 
                       class="border-b-2 border-indigo-600 text-indigo-600 py-4 px-1 font-semibold text-sm">
                        Plantillas
                    </a>
                    <a href="{{ route('admin.constancias.generar-nuevas') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Generar Nuevas
                    </a>
                </nav>
            </div>

            <!-- Plantillas Disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Plantilla de Ganador -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-1">Certificado de Ganador</h3>
                                <p class="text-pink-100 text-sm">Plantilla para certificados de ganadores</p>
                            </div>
                            <span class="px-3 py-1 bg-pink-700 rounded-full text-xs font-semibold">Activa</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Variables disponibles:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{recipientNombre}</code>
                                    <span class="text-gray-600">Nombre del participante</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{eventoNombre}</code>
                                    <span class="text-gray-600">Nombre del evento</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{equipoNombre}</code>
                                    <span class="text-gray-600">Nombre del equipo</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{proyectoNombre}</code>
                                    <span class="text-gray-600">Nombre del proyecto</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{codigoVerificacion}</code>
                                    <span class="text-gray-600">Código único</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plantilla de Participación -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-1">Certificado de Participación</h3>
                                <p class="text-purple-100 text-sm">Plantilla para certificados de participación</p>
                            </div>
                            <span class="px-3 py-1 bg-purple-700 rounded-full text-xs font-semibold">Activa</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Variables disponibles:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{recipientNombre}</code>
                                    <span class="text-gray-600">Nombre del participante</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{eventoNombre}</code>
                                    <span class="text-gray-600">Nombre del evento</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{equipoNombre}</code>
                                    <span class="text-gray-600">Nombre del equipo</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{proyectoNombre}</code>
                                    <span class="text-gray-600">Nombre del proyecto</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono">{codigoVerificacion}</code>
                                    <span class="text-gray-600">Código único</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
