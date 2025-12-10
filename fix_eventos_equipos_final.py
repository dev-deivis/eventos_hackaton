import re

print("="*60)
print("Corrección Final Dark Mode - Eventos y Equipos")
print("="*60)

def fix_file(filepath, fixes):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if content.startswith('\ufeff'):
            content = content[1:]
        
        original = content
        
        for old, new in fixes:
            content = content.replace(old, new)
        
        if content != original:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f'Error: {e}')
        return False

# ========== CORRECCIONES PARA eventos/show.blade.php ==========
eventos_fixes = [
    # 1. Premios - contenedor principal
    ('<div class="space-y-3">',
     '<div class="space-y-3">' if 'Premios' not in 'space-y-3' else '<div class="space-y-3">'),
    
    # 2. Premio individual - cambiar fondo
    ('<div class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">',
     '<div class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">'),
    
    # 3. Texto del premio (lugar y descripción) - primer texto
    ('<span class="font-bold text-gray-900 dark:text-white">{{ $premio->lugar }}:</span>',
     '<span class="font-bold text-gray-900 dark:text-white">{{ $premio->lugar }}:</span>'),
    
    # 4. Descripción del premio
    ('<span class="text-gray-700 dark:text-gray-300 dark:text-gray-600 dark:text-gray-300 ml-2">',
     '<span class="text-gray-700 dark:text-gray-300 ml-2">'),
    
    # 5. Equipos Participantes - título principal  
    ('<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">',
     '<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">'),
    
    # 6. Contenedor de equipo individual - CAMBIAR A AZUL
    ('<div class="border border-gray-200 dark:border-gray-600 dark:border-gray-700 rounded-lg p-4 mb-3 hover:border-indigo-300 dark:hover:border-indigo-500 transition">',
     '<div class="bg-indigo-600 dark:bg-indigo-700 border border-indigo-500 dark:border-indigo-600 rounded-lg p-4 mb-3 hover:border-indigo-400 dark:hover:border-indigo-500 transition">'),
    
    # 7. Nombre del equipo - BLANCO
    ('<h4 class="font-bold text-gray-900 dark:text-white">{{ $equipo->nombre }}</h4>',
     '<h4 class="font-bold text-white">{{ $equipo->nombre }}</h4>'),
    
    # 8. Descripción del equipo - BLANCO
    ('<p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">{{ $equipo->descripcion }}</p>',
     '<p class="text-sm text-white/90">{{ $equipo->descripcion }}</p>'),
    
    # 9. Botón "Ver Equipo" - BLANCO
    ('<a href="{{ route(\'equipos.show\', $equipo) }}" \n                                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:bg-gray-600 rounded-lg text-sm font-medium">',
     '<a href="{{ route(\'equipos.show\', $equipo) }}" \n                                       class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium text-white">'),
    
    # 10. Texto "Miembros" - BLANCO
    ('<span class="font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600">Miembros:',
     '<span class="font-medium text-white">Miembros:'),
]

# ========== CORRECCIONES PARA equipos/show.blade.php ==========
equipos_fixes = [
    # 1. Título "Miembros del Equipo" - debe ser BLANCO
    ('<h3 class="text-lg font-bold flex items-center gap-2">',
     '<h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">'),
    
    # 2. Título "Chat del Equipo" - debe ser BLANCO  
    ('<h3 class="font-bold flex items-center gap-2">',
     '<h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">'),
    
    # 3. Labels en formularios con duplicados
    ('<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">',
     '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">'),
    
    # 4. Textos de ayuda con duplicados
    ('<p class="text-xs text-gray-500 dark:text-gray-500">',
     '<p class="text-xs text-gray-500 dark:text-gray-400">'),
    
    # 5. Descripciones con duplicados
    ('<p class="text-gray-600 dark:text-gray-400 dark:text-gray-500">',
     '<p class="text-gray-600 dark:text-gray-400">'),
    
    # 6. Hover states
    ('hover:bg-gray-50 dark:bg-gray-700/50',
     'hover:bg-gray-50 dark:hover:bg-gray-700/50'),
]

# Aplicar correcciones
print("\n1. Corrigiendo eventos/show.blade.php...")
if fix_file('resources/views/eventos/show.blade.php', eventos_fixes):
    print('   ✓ Corregido')
else:
    print('   - Sin cambios')

print("\n2. Corrigiendo equipos/show.blade.php...")
if fix_file('resources/views/equipos/show.blade.php', equipos_fixes):
    print('   ✓ Corregido')
else:
    print('   - Sin cambios')

print("\n" + "="*60)
print("Correcciones aplicadas!")
print("="*60)
print("\nCambios realizados:")
print("  eventos/show.blade.php:")
print("    - Premios: fondo oscuro en cards")
print("    - Equipos participantes: fondo azul + texto blanco")
print("  equipos/show.blade.php:")
print("    - Títulos: color blanco en dark mode")
print("    - Labels y textos: limpieza de duplicados")

input("\nPresiona Enter para salir...")
