import os
import re
from pathlib import Path

print("="*60)
print("Corrección Final - Rankings Juez")
print("="*60)

def fix_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Eliminar BOM si existe
        if content.startswith('\ufeff'):
            content = content[1:]
        
        original = content
        
        # Correcciones específicas
        fixes = [
            # Nombre del equipo (el más importante)
            ('<h3 class="text-xl font-bold text-gray-900">{{ $equipo->nombre }}</h3>',
             '<h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $equipo->nombre }}</h3>'),
            
            # Puntuación final
            ('<div class="text-4xl font-bold text-gray-900">{{ number_format($equipo->calificacion_promedio, 0) }}</div>',
             '<div class="text-4xl font-bold text-gray-900 dark:text-white">{{ number_format($equipo->calificacion_promedio, 0) }}</div>'),
            
            # Badge de 2do lugar (corregir el texto duplicado)
            ("2 => ['bg' => 'bg-gray-200 dark:bg-gray-600', 'text' => 'text-gray-700 dark:text-gray-300 dark:text-gray-600',",
             "2 => ['bg' => 'bg-gray-200 dark:bg-gray-600', 'text' => 'text-gray-700 dark:text-gray-300',"),
            
            # Texto "Puntuación Final"
            ('<div class="text-xs text-gray-500">Puntuación Final</div>',
             '<div class="text-xs text-gray-500 dark:text-gray-400">Puntuación Final</div>'),
            
            # Título en empty state
            ('<h3 class="text-lg font-bold text-gray-900 mb-2">No hay equipos evaluados aún</h3>',
             '<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No hay equipos evaluados aún</h3>'),
            
            # Colores de puntajes individuales
            ('<span class="text-blue-600 font-bold">',
             '<span class="text-blue-600 dark:text-blue-400 font-bold">'),
            
            ('<span class="text-green-600 font-bold">',
             '<span class="text-green-600 dark:text-green-400 font-bold">'),
        ]
        
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

# Procesar rankings.blade.php
filepath = Path('resources/views/juez/rankings.blade.php')

print("\nCorrigiendo archivo...")
print("-" * 60)

if filepath.exists():
    print(f'Procesando: {filepath}')
    if fix_file(filepath):
        print('  ✓ Corregido')
    else:
        print('  - Sin cambios necesarios')
else:
    print(f"❌ Error: No se encontró {filepath}")

print("-" * 60)
print("\n" + "="*60)
print("¡Corrección aplicada!")
print("="*60)

input("\nPresiona Enter para salir...")
