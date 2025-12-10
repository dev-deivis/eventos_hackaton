import os
import re
from pathlib import Path

print("="*60)
print("Corrigiendo Rankings y Evaluaciones - Vistas de Juez")
print("="*60)

def fix_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Eliminar BOM si existe
        if content.startswith('\ufeff'):
            content = content[1:]
        
        original = content
        
        # Correcciones para rankings.blade.php y evaluaciones.blade.php
        replacements = [
            # Headers principales
            (r'<h1 class="text-3xl font-bold text-gray-900">', 
             r'<h1 class="text-3xl font-bold text-gray-900 dark:text-white">'),
            
            (r'<p class="text-gray-600 mt-1">', 
             r'<p class="text-gray-600 dark:text-gray-400 mt-1">'),
            
            # Cards de estadísticas
            (r'<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">',
             r'<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">'),
            
            # Títulos de cards
            (r'<h3 class="text-sm font-medium text-gray-600">',
             r'<h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">'),
            
            # Textos de descripción en cards
            (r'<p class="text-xs text-gray-500 mt-1">',
             r'<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">'),
            
            (r'<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">',
             r'<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">'),
            
            # Contenedores principales
            (r'<div class="bg-white rounded-xl shadow-sm border border-gray-100">',
             r'<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">'),
            
            # Bordes de división
            (r'border-b border-gray-100',
             r'border-b border-gray-100 dark:border-gray-700'),
            
            (r'divide-y divide-gray-100',
             r'divide-y divide-gray-100 dark:divide-gray-700'),
            
            # Títulos de secciones
            (r'<h2 class="text-xl font-bold text-gray-900">',
             r'<h2 class="text-xl font-bold text-gray-900 dark:text-white">'),
            
            (r'<p class="text-sm text-gray-600 mt-1">',
             r'<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">'),
            
            # Hover states
            (r'hover:bg-gray-50',
             r'hover:bg-gray-50 dark:hover:bg-gray-700/50'),
            
            # Nombres y textos principales
            (r'<h4 class="font-bold text-gray-900',
             r'<h4 class="font-bold text-gray-900 dark:text-white'),
            
            (r'<p class="text-sm text-gray-600">',
             r'<p class="text-sm text-gray-600 dark:text-gray-400">'),
            
            (r'<p class="text-xs text-gray-500">',
             r'<p class="text-xs text-gray-500 dark:text-gray-400">'),
            
            # Backgrounds de badges (ajustar todos los colores)
            (r'bg-indigo-100',
             r'bg-indigo-100 dark:bg-indigo-900/30'),
            
            (r'bg-purple-100',
             r'bg-purple-100 dark:bg-purple-900/30'),
            
            (r'bg-yellow-100',
             r'bg-yellow-100 dark:bg-yellow-900/30'),
            
            (r'bg-gray-100',
             r'bg-gray-100 dark:bg-gray-700'),
            
            (r'bg-gray-200',
             r'bg-gray-200 dark:bg-gray-600'),
            
            (r'bg-orange-100',
             r'bg-orange-100 dark:bg-orange-900/30'),
            
            (r'bg-green-100',
             r'bg-green-100 dark:bg-green-900/30'),
            
            # Text colors de badges
            (r'text-indigo-600',
             r'text-indigo-600 dark:text-indigo-400'),
            
            (r'text-purple-600',
             r'text-purple-600 dark:text-purple-400'),
            
            (r'text-yellow-600',
             r'text-yellow-600 dark:text-yellow-400'),
            
            (r'text-yellow-700',
             r'text-yellow-700 dark:text-yellow-300'),
            
            (r'text-gray-600',
             r'text-gray-600 dark:text-gray-400'),
            
            (r'text-gray-700',
             r'text-gray-700 dark:text-gray-300'),
            
            (r'text-orange-700',
             r'text-orange-700 dark:text-orange-300'),
            
            (r'text-pink-600',
             r'text-pink-600 dark:text-pink-400'),
            
            # Empty state
            (r'<div class="text-center py-12">',
             r'<div class="text-center py-12 dark:bg-gray-700/50 rounded-lg">'),
            
            (r'text-gray-300',
             r'text-gray-300 dark:text-gray-600'),
        ]
        
        for old, new in replacements:
            content = content.replace(old, new)
        
        # Limpiar duplicados
        content = re.sub(r'(dark:[\w-]+(?:/\d+)?)\s+\1', r'\1', content)
        
        if content != original:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f'Error: {e}')
        return False

# Procesar solo rankings.blade.php y evaluaciones.blade.php
views_path = Path('resources/views/juez')
files_to_fix = ['rankings.blade.php', 'evaluaciones.blade.php']
modified = 0

print("\nCorrigiendo archivos...")
print("-" * 60)

if views_path.exists():
    for filename in files_to_fix:
        filepath = views_path / filename
        if filepath.exists():
            print(f'Procesando: juez/{filename}')
            if fix_file(filepath):
                modified += 1
                print('  ✓ Corregido')
            else:
                print('  - Sin cambios')
        else:
            print(f'❌ No se encontró: juez/{filename}')
else:
    print(f"❌ Error: No se encontró la carpeta {views_path}")

print("-" * 60)
print(f'\n✓ Archivos corregidos: {modified}')
print("\n" + "="*60)
print("¡Correcciones aplicadas!")
print("="*60)

input("\nPresiona Enter para salir...")
