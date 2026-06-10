// LocalStorage Mock Database for LibraFlow

// Initial seeding data if empty
const defaultCategories = [
    { id: 1, nama: "Fiksi", deskripsi: "Novel dan cerita fiksi" },
    { id: 2, nama: "Sains", deskripsi: "Buku ilmu pengetahuan" },
    { id: 3, nama: "Teknologi", deskripsi: "Buku tentang teknologi dan komputer" },
    { id: 4, nama: "Sejarah", deskripsi: "Buku sejarah dan peradaban" },
    { id: 5, nama: "Psikologi", deskripsi: "Buku tentang psikologi dan pengembangan diri" },
    { id: 6, nama: "Agama", deskripsi: "Buku keagamaan" }
];

const defaultBooks = [
    { id: 1, kategori_id: 1, judul: "Laskar Pelangi", penulis: "Andrea Hirata", penerbit: "Bentang Pustaka", tahun_terbit: 2005, stok: 5 },
    { id: 2, kategori_id: 1, judul: "Bumi Manusia", penulis: "Pramoedya Ananta Toer", penerbit: "Hasta Mitra", tahun_terbit: 1980, stok: 3 },
    { id: 3, kategori_id: 2, judul: "Sapiens", penulis: "Yuval Noah Harari", penerbit: "Harper", tahun_terbit: 2011, stok: 4 },
    { id: 4, kategori_id: 3, judul: "Clean Code", penulis: "Robert C. Martin", penerbit: "Prentice Hall", tahun_terbit: 2008, stok: 2 },
    { id: 5, kategori_id: 4, judul: "Sejarah Indonesia Modern", penulis: "M.C. Ricklefs", penerbit: "Gadjah Mada UP", tahun_terbit: 2005, stok: 3 },
    { id: 6, kategori_id: 5, judul: "Atomic Habits", penulis: "James Clear", penerbit: "Avery", tahun_terbit: 2018, stok: 6 },
    { id: 7, kategori_id: 1, judul: "Negeri 5 Menara", penulis: "Ahmad Fuadi", penerbit: "Gramedia", tahun_terbit: 2009, stok: 4 },
    { id: 8, kategori_id: 3, judul: "The Pragmatic Programmer", penulis: "David Thomas", penerbit: "Addison-Wesley", tahun_terbit: 2019, stok: 2 }
];

const defaultUsers = [
    { id: 1, name: "Admin", email: "admin@libraflow.com", password: "admin", phone: "081234567890", role: "admin", created_at: "2026-06-04" },
    { id: 2, name: "Albert Benedict", email: "albert@gmail.com", password: "password", phone: "081357814360", role: "member", created_at: "2026-06-04" }
];

const defaultLoans = [
    { id: 1, user_id: 2, tgl_pinjam: "2026-06-01", tgl_kembali_rencana: "2026-06-08", tgl_kembali_aktual: null, status: "aktif", buku_id: 1 }
];

const defaultFines = [
    { id: 1, peminjaman_id: 1, jumlah_hari: 2, denda_harian: 1000, total: 2000, status: "belum_bayar", tgl_bayar: null }
];

// Initialize LocalStorage keys if they don't exist
function initDB() {
    if (!localStorage.getItem("lf_categories")) localStorage.setItem("lf_categories", JSON.stringify(defaultCategories));
    if (!localStorage.getItem("lf_books")) localStorage.setItem("lf_books", JSON.stringify(defaultBooks));
    if (!localStorage.getItem("lf_users")) localStorage.setItem("lf_users", JSON.stringify(defaultUsers));
    if (!localStorage.getItem("lf_loans")) localStorage.setItem("lf_loans", JSON.stringify(defaultLoans));
    if (!localStorage.getItem("lf_fines")) localStorage.setItem("lf_fines", JSON.stringify(defaultFines));
}
initDB();

// Database CRUD methods
const db = {
    // Categories
    getCategories() { return JSON.parse(localStorage.getItem("lf_categories")); },
    addCategory(cat) {
        const cats = this.getCategories();
        cat.id = cats.length ? Math.max(...cats.map(c => c.id)) + 1 : 1;
        cats.push(cat);
        localStorage.setItem("lf_categories", JSON.stringify(cats));
        return cat;
    },
    getCategory(id) { return this.getCategories().find(c => c.id == id); },
    updateCategory(id, updated) {
        const cats = this.getCategories();
        const idx = cats.findIndex(c => c.id == id);
        if (idx !== -1) {
            cats[idx] = { ...cats[idx], ...updated };
            localStorage.setItem("lf_categories", JSON.stringify(cats));
        }
    },
    deleteCategory(id) {
        const cats = this.getCategories().filter(c => c.id != id);
        localStorage.setItem("lf_categories", JSON.stringify(cats));
    },

    // Books
    getBooks() { return JSON.parse(localStorage.getItem("lf_books")); },
    addBook(book) {
        const books = this.getBooks();
        book.id = books.length ? Math.max(...books.map(b => b.id)) + 1 : 1;
        book.stok = parseInt(book.stok) || 0;
        book.tahun_terbit = parseInt(book.tahun_terbit) || new Date().getFullYear();
        books.push(book);
        localStorage.setItem("lf_books", JSON.stringify(books));
        return book;
    },
    getBook(id) { return this.getBooks().find(b => b.id == id); },
    updateBook(id, updated) {
        const books = this.getBooks();
        const idx = books.findIndex(b => b.id == id);
        if (idx !== -1) {
            books[idx] = { ...books[idx], ...updated };
            books[idx].stok = parseInt(books[idx].stok) || 0;
            books[idx].tahun_terbit = parseInt(books[idx].tahun_terbit) || new Date().getFullYear();
            localStorage.setItem("lf_books", JSON.stringify(books));
        }
    },
    deleteBook(id) {
        const books = this.getBooks().filter(b => b.id != id);
        localStorage.setItem("lf_books", JSON.stringify(books));
    },

    // Users
    getUsers() { return JSON.parse(localStorage.getItem("lf_users")); },
    addUser(user) {
        const users = this.getUsers();
        user.id = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1;
        user.role = user.role || 'member';
        user.created_at = new Date().toISOString().split('T')[0];
        users.push(user);
        localStorage.setItem("lf_users", JSON.stringify(users));
        return user;
    },
    getUser(id) { return this.getUsers().find(u => u.id == id); },
    deleteUser(id) {
        const users = this.getUsers().filter(u => u.id != id);
        localStorage.setItem("lf_users", JSON.stringify(users));
    },

    // Loans
    getLoans() { return JSON.parse(localStorage.getItem("lf_loans")); },
    addLoan(loan) {
        const loans = this.getLoans();
        loan.id = loans.length ? Math.max(...loans.map(l => l.id)) + 1 : 1;
        loan.status = loan.status || 'aktif';
        loans.push(loan);
        localStorage.setItem("lf_loans", JSON.stringify(loans));

        // Deduct book stock
        const book = this.getBook(loan.buku_id);
        if (book && book.stok > 0) {
            this.updateBook(loan.buku_id, { stok: book.stok - 1 });
        }
        return loan;
    },
    getLoan(id) { return this.getLoans().find(l => l.id == id); },
    updateLoan(id, updated) {
        const loans = this.getLoans();
        const idx = loans.findIndex(l => l.id == id);
        if (idx !== -1) {
            const oldStatus = loans[idx].status;
            loans[idx] = { ...loans[idx], ...updated };
            localStorage.setItem("lf_loans", JSON.stringify(loans));

            // If status changed to dikembalikan, restore book stock
            if (oldStatus !== 'dikembalikan' && updated.status === 'dikembalikan') {
                const book = this.getBook(loans[idx].buku_id);
                if (book) {
                    this.updateBook(loans[idx].buku_id, { stok: book.stok + 1 });
                }
            }
        }
    },

    // Fines
    getFines() { return JSON.parse(localStorage.getItem("lf_fines")); },
    addFine(fine) {
        const fines = this.getFines();
        fine.id = fines.length ? Math.max(...fines.map(f => f.id)) + 1 : 1;
        fines.push(fine);
        localStorage.setItem("lf_fines", JSON.stringify(fines));
        return fine;
    },
    payFine(id) {
        const fines = this.getFines();
        const idx = fines.findIndex(f => f.id == id);
        if (idx !== -1) {
            fines[idx].status = 'lunas';
            fines[idx].tgl_bayar = new Date().toISOString().split('T')[0];
            localStorage.setItem("lf_fines", JSON.stringify(fines));
        }
    },

    // Session Management
    login(email, password) {
        const users = this.getUsers();
        // Allow standard passwords or admin/password for simplicity in simulation
        const user = users.find(u => u.email === email && (password === 'admin' || password === 'password' || u.password === password));
        if (user) {
            localStorage.setItem("lf_session", JSON.stringify(user));
            return user;
        }
        return null;
    },
    logout() {
        localStorage.removeItem("lf_session");
    },
    getCurrentUser() {
        return JSON.parse(localStorage.getItem("lf_session"));
    },
    checkAuth(roleRequired) {
        const user = this.getCurrentUser();
        if (!user) {
            const prefix = window.location.pathname.includes('/admin/') || window.location.pathname.includes('/member/') ? '../' : '';
            window.location.href = prefix + 'login.html';
            return null;
        }
        if (roleRequired && user.role !== roleRequired) {
            const prefix = window.location.pathname.includes('/admin/') || window.location.pathname.includes('/member/') ? '../' : '';
            window.location.href = prefix + (user.role === 'admin' ? 'admin/dashboard.html' : 'member/dashboard.html');
            return null;
        }
        return user;
    }
};

// Auto-inject Mobile responsive toggles and overlay for Dashboard sidebar navigation
document.addEventListener("DOMContentLoaded", function() {
    const dashboardEl = document.querySelector('.dashboard');
    if (dashboardEl) {
        const mainContent = document.querySelector('.main-content');
        const sidebar = document.querySelector('.sidebar');
        
        if (mainContent && sidebar) {
            // Create mobile header bar
            const mobileHeader = document.createElement('div');
            mobileHeader.className = 'mobile-header';
            mobileHeader.innerHTML = `
                <button class="menu-toggle" id="lf-mobile-menu-btn" style="background: #16a34a; color: #fff; border: none; padding: 8px 14px; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 13px;">☰ Menu</button>
                <span class="mobile-logo" style="font-weight: bold; font-size: 15px; color: #fff;">📚 LibraFlow</span>
            `;
            
            // Prepend mobile header to main-content
            mainContent.insertBefore(mobileHeader, mainContent.firstChild);
            
            // Create overlay element
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.id = 'lf-sidebar-overlay';
            dashboardEl.appendChild(overlay);
            
            // Toggle functions
            const toggleMenu = () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            };
            
            document.getElementById('lf-mobile-menu-btn').addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        }
    }
});
