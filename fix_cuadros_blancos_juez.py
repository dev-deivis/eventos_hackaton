import os
import re
from pathlib import Path

print("="*60)
print("Corrigiendo Cuadros Blancos Restantes en Vistas de Juez")
print("="*60)

def fix_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Eliminar BOM si existe
        if content.startswith('\ufeff'):
            content = content[1:]
        
        original = content
        
        # Correcciones específicas para los cuadros blancos restantes
        fixes = [
            # Contenedor principal de "Equipos Pendientes"
            (r'<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100">', 
             r'<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">'),
            
            # Cards individuales de equipos
            (r'border-l-4 border-\{\{ \$proyecto && \$proyecto->estadoColor \? \$proyecto->estadoColor : \'gray\' \}\}-500 bg-white dark:bg-gray-800',
             r'border-l-4 border-{{ $proyecto && $proyecto->estadoColor ? $proyecto->estadoColor : \'gray\' }}-500 bg-white dark:bg-gray-700'),
            
            # Estado vacío
            (r'<div class="text-center py-12 bg-gray-50 rounded-lg">',
             r'<div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-lg">'),
            
            # Botón "Mis Evaluaciones"
            (r'bg-white dark:bg-gray-800 hover:bg-gray-50 border-2 border-gray-200 dark:border-gray-700',
             r'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-2 border-gray-200 dark:border-gray-600'),
            
            # Textos en botones disabled
            (r'bg-gray-300 text-gray-500 dark:text-gray-400',
             r'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400'),
            
            # Textos en cards
            (r'text-gray-700">Mis Evaluaciones',
             r'text-gray-700 dark:text-gray-300">Mis Evaluaciones'),
            
            # Textos en empty state
            (r'text-gray-600">¡Excelente trabajo!',
             r'text-gray-600 dark:text-gray-300">¡Excelente trabajo!'),
        ]
        
        for pattern, replacement in fixes:
            content = re.sub(re.escape(pattern), replacement, content)
        
        if content != original:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f'Error: {e}')
        return False

# Procesar archivos de juez
views_path = Path('resources/views/juez')
modified = 0

print("\nCorrigiendo archivos...")
print("-" * 60)

if views_path.exists():
    for blade in views_path.glob('*.blade.php'):
        rel_path = blade.relative_to(Path('resources/views'))
        print(f'Procesando: {rel_path}')
        if fix_file(blade):
            modified += 1
            print('  ✓ Corregido')
        else:
            print('  - Sin cambios')
else:
    print(f"❌ Error: No se encontró la carpeta {views_path}")

print("-" * 60)
print(f'\n✓ Archivos corregidos: {modified}')
print("\n" + "="*60)
print("¡Correcciones aplicadas!")
print("="*60)

input("\nPresiona Enter para salir...")
