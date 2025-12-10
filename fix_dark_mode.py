import os
import re
from pathlib import Path

print("="*60)
print("Aplicando Modo Oscuro a Vistas de Usuario")
print("="*60)

# Reemplazos
REPLACEMENTS = [
    # Fondos principales
    (r'class="([^"]*?)bg-white([^"]*?)"', r'class="\1bg-white dark:bg-gray-800\2"'),
    (r'class="([^"]*?)border-gray-100([^"]*?)"', r'class="\1border-gray-100 dark:border-gray-700\2"'),
    (r'class="([^"]*?)border-gray-200([^"]*?)"', r'class="\1border-gray-200 dark:border-gray-600\2"'),
    
    # Textos
    (r'class="([^"]*?)text-gray-900\b([^"]*?)"', r'class="\1text-gray-900 dark:text-white\2"'),
    (r'class="([^"]*?)text-gray-800\b([^"]*?)"', r'class="\1text-gray-800 dark:text-gray-200\2"'),
    (r'class="([^"]*?)text-gray-700\b([^"]*?)"', r'class="\1text-gray-700 dark:text-gray-300\2"'),
    (r'class="([^"]*?)text-gray-600\b([^"]*?)"', r'class="\1text-gray-600 dark:text-gray-400\2"'),
    (r'class="([^"]*?)text-gray-500\b([^"]*?)"', r'class="\1text-gray-500 dark:text-gray-500\2"'),
    (r'class="([^"]*?)text-gray-400\b([^"]*?)"', r'class="\1text-gray-400 dark:text-gray-500\2"'),
    (r'class="([^"]*?)text-gray-300\b([^"]*?)"', r'class="\1text-gray-300 dark:text-gray-600\2"'),
    
    # Fondos alternativos
    (r'class="([^"]*?)bg-gray-50\b([^"]*?)"', r'class="\1bg-gray-50 dark:bg-gray-700/50\2"'),
    (r'class="([^"]*?)bg-gray-100\b([^"]*?)"', r'class="\1bg-gray-100 dark:bg-gray-700\2"'),
    (r'class="([^"]*?)bg-gray-200\b([^"]*?)"', r'class="\1bg-gray-200 dark:bg-gray-600\2"'),
    
    # Colores - Indigo
    (r'class="([^"]*?)bg-indigo-50\b([^"]*?)"', r'class="\1bg-indigo-50 dark:bg-indigo-900/30\2"'),
    (r'class="([^"]*?)bg-indigo-100\b([^"]*?)"', r'class="\1bg-indigo-100 dark:bg-indigo-900\2"'),
    (r'class="([^"]*?)text-indigo-700\b([^"]*?)"', r'class="\1text-indigo-700 dark:text-indigo-300\2"'),
    (r'class="([^"]*?)text-indigo-600\b([^"]*?)"', r'class="\1text-indigo-600 dark:text-indigo-400\2"'),
    (r'class="([^"]*?)bg-indigo-600\b([^"]*?)"', r'class="\1bg-indigo-600 dark:bg-indigo-500\2"'),
    (r'hover:bg-indigo-700\b', r'hover:bg-indigo-700 dark:hover:bg-indigo-600'),
    (r'hover:text-indigo-700\b', r'hover:text-indigo-700 dark:hover:text-indigo-300'),
    (r'hover:bg-indigo-100\b', r'hover:bg-indigo-100 dark:hover:bg-indigo-900/50'),
    (r'hover:border-indigo-300\b', r'hover:border-indigo-300 dark:hover:border-indigo-500'),
    (r'group-hover:text-indigo-600\b', r'group-hover:text-indigo-600 dark:group-hover:text-indigo-400'),
    
    # Purple
    (r'class="([^"]*?)bg-purple-50\b([^"]*?)"', r'class="\1bg-purple-50 dark:bg-purple-900/30\2"'),
    (r'class="([^"]*?)bg-purple-100\b([^"]*?)"', r'class="\1bg-purple-100 dark:bg-purple-900\2"'),
    (r'class="([^"]*?)text-purple-700\b([^"]*?)"', r'class="\1text-purple-700 dark:text-purple-300\2"'),
    (r'class="([^"]*?)text-purple-600\b([^"]*?)"', r'class="\1text-purple-600 dark:text-purple-400\2"'),
    (r'hover:text-purple-700\b', r'hover:text-purple-700 dark:hover:text-purple-300'),
    (r'hover:bg-purple-100\b', r'hover:bg-purple-100 dark:hover:bg-purple-900/50'),
    
    # Blue
    (r'class="([^"]*?)bg-blue-50\b([^"]*?)"', r'class="\1bg-blue-50 dark:bg-blue-900/30\2"'),
    (r'class="([^"]*?)bg-blue-100\b([^"]*?)"', r'class="\1bg-blue-100 dark:bg-blue-900\2"'),
    (r'class="([^"]*?)text-blue-700\b([^"]*?)"', r'class="\1text-blue-700 dark:text-blue-300\2"'),
    (r'class="([^"]*?)text-blue-600\b([^"]*?)"', r'class="\1text-blue-600 dark:text-blue-400\2"'),
    (r'class="([^"]*?)text-blue-800\b([^"]*?)"', r'class="\1text-blue-800 dark:text-blue-300\2"'),
    (r'hover:text-blue-700\b', r'hover:text-blue-700 dark:hover:text-blue-300'),
    
    # Green
    (r'class="([^"]*?)bg-green-50\b([^"]*?)"', r'class="\1bg-green-50 dark:bg-green-900/30\2"'),
    (r'class="([^"]*?)bg-green-100\b([^"]*?)"', r'class="\1bg-green-100 dark:bg-green-900\2"'),
    (r'class="([^"]*?)text-green-700\b([^"]*?)"', r'class="\1text-green-700 dark:text-green-300\2"'),
    (r'class="([^"]*?)text-green-600\b([^"]*?)"', r'class="\1text-green-600 dark:text-green-400\2"'),
    (r'class="([^"]*?)text-green-800\b([^"]*?)"', r'class="\1text-green-800 dark:text-green-300\2"'),
    
    # Yellow
    (r'class="([^"]*?)bg-yellow-50\b([^"]*?)"', r'class="\1bg-yellow-50 dark:bg-yellow-900/30\2"'),
    (r'class="([^"]*?)bg-yellow-100\b([^"]*?)"', r'class="\1bg-yellow-100 dark:bg-yellow-900\2"'),
    (r'class="([^"]*?)text-yellow-700\b([^"]*?)"', r'class="\1text-yellow-700 dark:text-yellow-300\2"'),
    (r'class="([^"]*?)text-yellow-600\b([^"]*?)"', r'class="\1text-yellow-600 dark:text-yellow-400\2"'),
    (r'class="([^"]*?)text-yellow-800\b([^"]*?)"', r'class="\1text-yellow-800 dark:text-yellow-300\2"'),
    
    # Red
    (r'class="([^"]*?)bg-red-50\b([^"]*?)"', r'class="\1bg-red-50 dark:bg-red-900/30\2"'),
    (r'class="([^"]*?)bg-red-100\b([^"]*?)"', r'class="\1bg-red-100 dark:bg-red-900\2"'),
    (r'class="([^"]*?)text-red-700\b([^"]*?)"', r'class="\1text-red-700 dark:text-red-300\2"'),
    (r'class="([^"]*?)text-red-600\b([^"]*?)"', r'class="\1text-red-600 dark:text-red-400\2"'),
    (r'class="([^"]*?)text-red-800\b([^"]*?)"', r'class="\1text-red-800 dark:text-red-300\2"'),
    
    # Pink
    (r'class="([^"]*?)bg-pink-50\b([^"]*?)"', r'class="\1bg-pink-50 dark:bg-pink-900/30\2"'),
    (r'class="([^"]*?)bg-pink-100\b([^"]*?)"', r'class="\1bg-pink-100 dark:bg-pink-900\2"'),
    (r'class="([^"]*?)text-pink-700\b([^"]*?)"', r'class="\1text-pink-700 dark:text-pink-300\2"'),
    (r'class="([^"]*?)text-pink-600\b([^"]*?)"', r'class="\1text-pink-600 dark:text-pink-400\2"'),
    (r'hover:bg-pink-100\b', r'hover:bg-pink-100 dark:hover:bg-pink-900/50'),
    
    # Orange/Emerald/Amber
    (r'class="([^"]*?)bg-orange-50\b([^"]*?)"', r'class="\1bg-orange-50 dark:bg-orange-900/30\2"'),
    (r'class="([^"]*?)bg-orange-100\b([^"]*?)"', r'class="\1bg-orange-100 dark:bg-orange-900\2"'),
    (r'class="([^"]*?)text-orange-700\b([^"]*?)"', r'class="\1text-orange-700 dark:text-orange-300\2"'),
    (r'class="([^"]*?)bg-emerald-50\b([^"]*?)"', r'class="\1bg-emerald-50 dark:bg-emerald-900/30\2"'),
    (r'class="([^"]*?)bg-emerald-100\b([^"]*?)"', r'class="\1bg-emerald-100 dark:bg-emerald-900\2"'),
    (r'class="([^"]*?)bg-amber-50\b([^"]*?)"', r'class="\1bg-amber-50 dark:bg-amber-900/30\2"'),
    (r'class="([^"]*?)bg-amber-100\b([^"]*?)"', r'class="\1bg-amber-100 dark:bg-amber-900\2"'),
]

def fix_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        original = content
        for pattern, replacement in REPLACEMENTS:
            content = re.sub(pattern, replacement, content)
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

exclude = {'admin', 'juez', 'auth', 'layouts', 'components', 'emails'}
views_path = Path('resources/views')
processed = 0
modified = 0

print("\nProcesando archivos...")
print("-" * 60)

for blade in views_path.rglob('*.blade.php'):
    if any(ex in blade.parts for ex in exclude):
        continue
    rel_path = blade.relative_to(views_path)
    print(f'Procesando: {rel_path}')
    processed += 1
    if fix_file(blade):
        modified += 1
        print('  ✓ Modificado')
    else:
        print('  - Sin cambios')

print("-" * 60)
print(f'\n✓ Archivos procesados: {processed}')
print(f'✓ Archivos modificados: {modified}')
print("\n" + "="*60)
print("¡Modo oscuro aplicado exitosamente!")
print("="*60)

input("\nPresiona Enter para salir...")
