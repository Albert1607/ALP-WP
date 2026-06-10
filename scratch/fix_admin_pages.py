import os
import re

admin_dir = r"c:\Users\alber\OneDrive\Documents\ALP-WP\admin"

# Mapping of file names to their corresponding active sidebar menu item
active_mapping = {
    "dashboard.php": "dashboard.php",
    "buku.php": "buku.php",
    "tambah_buku.php": "buku.php",
    "edit_buku.php": "buku.php",
    "kategori.php": "kategori.php",
    "tambah_kategori.php": "kategori.php",
    "edit_kategori.php": "kategori.php",
    "peminjaman.php": "peminjaman.php",
    "tambah_peminjaman.php": "peminjaman.php",
    "detail_peminjaman.php": "peminjaman.php",
    "users.php": "users.php",
    "denda.php": "denda.php"
}

files_to_fix = [f for f in os.listdir(admin_dir) if f.endswith(".php") and f in active_mapping]

for filename in files_to_fix:
    filepath = os.path.join(admin_dir, filename)
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    # 1. Remove session_start() at the top if present to avoid session warning notices
    # We match session_start(); with potential whitespace
    content = re.sub(r"^\s*session_start\(\);\s*\n", "", content, flags=re.MULTILINE)
    content = re.sub(r"<\?php\s*session_start\(\);", "<?php", content)

    # 2. Re-write the sidebar structure
    # We want to find the block from <div class='sidebar'> up to the closing divs before <div class='main-content'>
    # Let's inspect the layout of the sidebar we want to inject.
    active_item = active_mapping[filename]
    
    sidebar_html = f"""    <div class='sidebar'>
        <div class='logo'>📚 LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class='{"active" if active_item == "dashboard.php" else ""}'>📊 Dashboard</a>
            <a href='buku.php' class='{"active" if active_item == "buku.php" else ""}'>📚 Buku</a>
            <a href='kategori.php' class='{"active" if active_item == "kategori.php" else ""}'>📂 Kategori</a>
            <a href='peminjaman.php' class='{"active" if active_item == "peminjaman.php" else ""}'>📋 Peminjaman</a>
            <a href='users.php' class='{"active" if active_item == "users.php" else ""}'>👥 Users</a>
            <a href='denda.php' class='{"active" if active_item == "denda.php" else ""}'>💰 Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'>🚪 Logout</a>
        </div>
    </div>"""

    # We match: <div class='sidebar'> ... </div>\s*</div>\s*(?=<div class='main-content'>)
    # Using re.DOTALL to match across lines
    pattern = r"<div class=['\"]sidebar['\"].*?</div>\s*</div>\s*(?=\s*<div class=['\"]main-content['\"]>)"
    
    new_content, count = re.subn(pattern, sidebar_html + "\n", content, flags=re.DOTALL)
    
    if count > 0:
        print(f"Fixed sidebar in {filename}")
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(new_content)
    else:
        print(f"WARNING: Sidebar pattern not matched in {filename}")
