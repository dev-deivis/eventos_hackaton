import re

print("="*60)
print("Corrigiendo Modo Oscuro - Formulario Crear Equipo")
print("="*60)

filepath = 'resources/views/equipos/create.blade.php'

try:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Eliminar BOM si existe
    if content.startswith('\ufeff'):
        content = content[1:]
    
    original = content
    
    # Correcciones específicas
    
    # 1. Input de nombre
    content = content.replace(
        'class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error(\'nombre\') border-red-500 @enderror">',
        'class="block w-full px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder:text-gray-400 dark:placeholder:text-gray-500 @error(\'nombre\') border-red-500 @enderror">'
    )
    
    # 2. Textarea de descripción
    content = content.replace(
        'class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none @error(\'descripcion\') border-red-500 @enderror">',
        'class="block w-full px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none placeholder:text-gray-400 dark:placeholder:text-gray-500 @error(\'descripcion\') border-red-500 @enderror">'
    )
    
    # 3. Select de rol
    content = content.replace(
        'class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error(\'perfil_id\') border-red-500 @enderror">',
        'class="block w-full px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error(\'perfil_id\') border-red-500 @enderror">'
    )
    
    # 4. Corregir labels con texto duplicado
    content = content.replace(
        'class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">',
        'class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">'
    )
    
    # 5. Corregir textos de ayuda
    content = content.replace(
        'class="text-xs text-gray-500 dark:text-gray-500">',
        'class="text-xs text-gray-500 dark:text-gray-400">'
    )
    
    content = content.replace(
        'class="mt-1 text-sm text-gray-500 dark:text-gray-500">',
        'class="mt-1 text-sm text-gray-500 dark:text-gray-400">'
    )
    
    # 6. Corregir botón cancelar
    content = content.replace(
        'class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-300 dark:text-gray-600 hover:bg-gray-50 dark:bg-gray-700/50 font-medium transition duration-200">',
        'class="px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition duration-200">'
    )
    
    # 7. Corregir descripción del header duplicada
    content = content.replace(
        '<p class="text-gray-600 dark:text-gray-400 dark:text-gray-500">',
        '<p class="text-gray-600 dark:text-gray-400">'
    )
    
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'\n✓ Archivo corregido: {filepath}')
        print('\nCambios aplicados:')
        print('  - Input nombre: fondo oscuro añadido')
        print('  - Textarea descripción: fondo oscuro añadido')
        print('  - Select rol: fondo oscuro añadido')
        print('  - Labels: texto duplicado corregido')
        print('  - Textos de ayuda: colores corregidos')
        print('  - Botón cancelar: fondo oscuro añadido')
    else:
        print('\n- Sin cambios necesarios')
    
    print("\n" + "="*60)
    print("¡Corrección completada!")
    print("="*60)
    
except Exception as e:
    print(f'\n❌ Error: {e}')

input("\nPresiona Enter para salir...")
