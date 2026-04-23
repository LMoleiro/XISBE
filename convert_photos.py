import os
import re
import shutil
import unicodedata
from pathlib import Path

import pillow_heif
pillow_heif.register_heif_opener()
from PIL import Image

FOTOS_BASE = r"c:\Users\HP\Desktop\SBE\XISBE\Fotos"
IMAGES_DEST = r"c:\Users\HP\Desktop\SBE\XISBE\assets\images"

DEPARTMENTS = [
    ("Cientifico",         "cie", "Scientific Department"),
    ("Comunicação",        "com", "Communication Department"),
    ("Coordenação",        "coo", "Coordination Department"),
    ("Design",             "des", "Design Department"),
    ("Informática",        "inf", "Informatics Department"),
    ("Logística",          "log", "Logistics Department"),
    ("Relações Externas",  "re",  "External Relations Department"),
]

def slugify(name):
    normalized = unicodedata.normalize('NFD', name)
    ascii_name = ''.join(c for c in normalized if unicodedata.category(c) != 'Mn')
    return re.sub(r'[^a-z0-9]', '', ascii_name.lower())

def extract_name(filename):
    stem = Path(filename).stem
    stem = stem.replace('_', ' ').strip(' _')
    return ' '.join(w.capitalize() for w in stem.split())

results = {}  # {dept_name: [(person_name, img_src), ...]}

for folder, prefix, dept_label in DEPARTMENTS:
    folder_path = os.path.join(FOTOS_BASE, folder)
    people = []
    for filename in sorted(os.listdir(folder_path)):
        src_path = os.path.join(folder_path, filename)
        if not os.path.isfile(src_path):
            continue
        person_name = extract_name(filename)
        slug = slugify(person_name)
        dest_filename = f"{prefix}_{slug}.jpg"
        dest_path = os.path.join(IMAGES_DEST, dest_filename)
        try:
            img = Image.open(src_path)
            img = img.convert('RGB')
            img.save(dest_path, 'JPEG', quality=85)
            print(f"OK  {filename} -> {dest_filename}")
        except Exception as e:
            print(f"ERR {filename}: {e}")
            dest_filename = None
        if dest_filename:
            people.append((person_name, f"assets/images/{dest_filename}"))
    results[dept_label] = people

# Print summary
print("\n=== RESULTS ===")
for dept, people in results.items():
    print(f"\n{dept}:")
    for name, src in people:
        print(f"  {name} -> {src}")
