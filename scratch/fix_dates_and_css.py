import os
import re

base_dir = r"c:\Users\alber\OneDrive\Documents\ALP-WP"

# 1. Update CSS to override form-group input style for checkboxes/radios
css_path = os.path.join(base_dir, "assets", "css", "style.css")
with open(css_path, "r", encoding="utf-8") as f:
    css_content = f.read()

checkbox_style = """
/* Fix checkbox stretching in forms */
.form-group input[type="checkbox"],
.form-group input[type="radio"] {
    width: auto !important;
    height: auto !important;
    margin-right: 8px;
    cursor: pointer;
}
"""

if "Fix checkbox stretching" not in css_content:
    with open(css_path, "a", encoding="utf-8") as f:
        f.write(checkbox_style)
    print("Appended checkbox style override to style.css")

# 2. Fix dates in files to use dd-mm-yyyy format
replacements = {
    # Filepath relative to base_dir : List of (pattern, replacement)
    "admin/dashboard.php": [
        (r"<td>\s*<\?=\s*\$row\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_rencana'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>"),
    ],
    "admin/denda.php": [
        (r"<td>\s*<\?=\s*\(!empty\(\$row\['tgl_bayar'\]\)\)\s*\?\s*\$row\['tgl_bayar'\]\s*:\s*'-'\s*\?>\s*</td>", "<td><?= (!empty($row['tgl_bayar'])) ? date('d-m-Y', strtotime($row['tgl_bayar'])) : '-' ?></td>"),
    ],
    "admin/detail_peminjaman.php": [
        (r"<p>\s*<\?=\s*\$peminjaman\['tgl_pinjam'\]\s*\?>\s*</p>", "<p><?= date('d-m-Y', strtotime($peminjaman['tgl_pinjam'])) ?></p>"),
        (r"<p>\s*<\?=\s*\$peminjaman\['tgl_kembali_rencana'\]\s*\?>\s*</p>", "<p><?= date('d-m-Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></p>"),
        (r"<p>\s*<\?=\s*\$peminjaman\['tgl_kembali_aktual'\]\s*\?\s*\$peminjaman\['tgl_kembali_aktual'\]\s*:\s*'-'\s*\?>\s*</p>", "<p><?= $peminjaman['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($peminjaman['tgl_kembali_aktual'])) : '-' ?></p>"),
    ],
    "admin/peminjaman.php": [
        (r"<td>\s*<\?=\s*\$row\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_rencana'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_aktual'\]\s*\?\s*\$row\['tgl_kembali_aktual'\]\s*:\s*'-'\s*\?>\s*</td>", "<td><?= $row['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($row['tgl_kembali_aktual'])) : '-' ?></td>"),
    ],
    "admin/users.php": [
        (r"<td>\s*<\?=\s*\$row\['created_at'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>"),
    ],
    "member/dashboard.php": [
        (r"<td>\s*<\?=\s*\$row\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_rencana'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>"),
    ],
    "member/denda.php": [
        (r"<td>\s*<\?=\s*\$row\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_bayar'\]\s*\?\s*\$row\['tgl_bayar'\]\s*:\s*'-'\s*\?>\s*</td>", "<td><?= $row['tgl_bayar'] ? date('d-m-Y', strtotime($row['tgl_bayar'])) : '-' ?></td>"),
    ],
    "member/detail_peminjaman.php": [
        (r"<td>\s*<\?=\s*\$peminjaman\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($peminjaman['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$peminjaman\['tgl_kembali_rencana'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$peminjaman\['tgl_kembali_aktual'\]\s*\?\s*\$peminjaman\['tgl_kembali_aktual'\]\s*:\s*'-'\s*\?>\s*</td>", "<td><?= $peminjaman['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($peminjaman['tgl_kembali_aktual'])) : '-' ?></td>"),
    ],
    "member/peminjaman.php": [
        (r"<td>\s*<\?=\s*\$row\['tgl_pinjam'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_rencana'\]\s*\?>\s*</td>", "<td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>"),
        (r"<td>\s*<\?=\s*\$row\['tgl_kembali_aktual'\]\s*\?\s*\$row\['tgl_kembali_aktual'\]\s*:\s*'-'\s*\?>\s*</td>", "<td><?= $row['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($row['tgl_kembali_aktual'])) : '-' ?></td>"),
    ]
}

for rel_path, file_replacements in replacements.items():
    filepath = os.path.join(base_dir, rel_path)
    if not os.path.exists(filepath):
        print(f"Skipping non-existent file: {filepath}")
        continue
    
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    new_content = content
    for pattern, replacement in file_replacements:
        new_content, count = re.subn(pattern, replacement, new_content)
        if count > 0:
            print(f"Replaced pattern in {rel_path} ({count} times)")
        else:
            print(f"WARNING: Pattern not matched in {rel_path}: {pattern}")
            
    if new_content != content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(new_content)
