import re

print("="*60)
print("Corrección BOTONES Equipos Participantes")
print("="*60)

filepath = 'resources/views/eventos/show.blade.php'

try:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if content.startswith('\ufeff'):
        content = content[1:]
    
    original = content
    
    # Revertir el card a estilo normal gris
    content = content.replace(
        '<div class="bg-indigo-600 dark:bg-indigo-700 border border-indigo-500 dark:border-indigo-600 rounded-lg p-4 mb-3 hover:border-indigo-400 dark:hover:border-indigo-500 transition">',
        '<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-3 hover:border-indigo-300 dark:hover:border-indigo-500 transition">'
    )
    
    # Nombre del equipo - volver a gris
    content = content.replace(
        '<h4 class="font-bold text-white">{{ $equipo->nombre }}</h4>',
        '<h4 class="font-bold text-gray-900 dark:text-white">{{ $equipo->nombre }}</h4>'
    )
    
    # Descripción - volver a gris
    content = content.replace(
        '<p class="text-sm text-white/90">{{ $equipo->descripcion }}</p>',
        '<p class="text-sm text-gray-600 dark:text-gray-400">{{ $equipo->descripcion }}</p>'
    )
    
    # Botón "Ver Equipo" - AZUL con letras blancas
    content = content.replace(
        '<a href="{{ route(\'equipos.show\', $equipo) }}" \n                                       class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium text-white">',
        '<a href="{{ route(\'equipos.show\', $equipo) }}" \n                                       class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 rounded-lg text-sm font-medium text-white">'
    )
    
    # Texto "Miembros" - volver a gris
    content = content.replace(
        '<span class="font-medium text-white">Miembros:',
        '<span class="font-medium text-gray-700 dark:text-gray-300">Miembros:'
    )
    
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print('\n✓ Archivo corregido: resources/views/eventos/show.blade.php')
        print('\nCambios aplicados:')
        print('  - Card del equipo: fondo gris normal')
        print('  - Nombre del equipo: texto gris/blanco normal')
        print('  - Descripción: texto gris normal')
        print('  - Botón "Ver Equipo": AZUL con texto blanco ✓')
        print('  - Texto "Miembros": gris normal')
    else:
        print('\n- Sin cambios necesarios')
    
    print("\n" + "="*60)
    print("¡Corrección completada!")
    print("="*60)
    
except Exception as e:
    print(f'\n❌ Error: {e}')

input("\nPresiona Enter para salir...")
