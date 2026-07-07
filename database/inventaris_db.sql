-- =====================================================================
-- inventaris_db.sql
-- Skema & data awal Sistem Manajemen Inventaris (PostgreSQL)
-- Cocok diimport langsung lewat pgAdmin (Query Tool) sebagai alternatif
-- dari menjalankan `php artisan migrate --seed`.
--
-- CATATAN:
-- - Jika Anda mengimpor file ini secara manual, JANGAN jalankan
--   `php artisan migrate` sesudahnya (akan bentrok karena tabel sudah ada).
--   Cukup jalankan `php artisan migrate:status` untuk cek, atau lewati saja.
-- - Password semua akun contoh di bawah adalah: password
-- =====================================================================

BEGIN;

-- ----------------------------- ----------------------------------------
-- Tabel: roles
-- ---------------------------------------------------------------------
CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    label VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: users
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE,
    password VARCHAR(255) NOT NULL,
    role_id BIGINT REFERENCES roles(id) ON DELETE SET NULL,
    avatar VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: password_reset_tokens
-- ---------------------------------------------------------------------
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: sessions
-- ---------------------------------------------------------------------
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX sessions_user_id_index ON sessions (user_id);
CREATE INDEX sessions_last_activity_index ON sessions (last_activity);

-- ---------------------------------------------------------------------
-- Tabel: cache & cache_locks
-- ---------------------------------------------------------------------
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- ---------------------------------------------------------------------
-- Tabel: jobs, job_batches, failed_jobs (queue bawaan Laravel)
-- ---------------------------------------------------------------------
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX jobs_queue_index ON jobs (queue);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT,
    cancelled_at INTEGER,
    created_at INTEGER NOT NULL,
    finished_at INTEGER
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------
-- Tabel: categories
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: products
-- ---------------------------------------------------------------------
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    kode_barang VARCHAR(255) NOT NULL UNIQUE,
    nama_barang VARCHAR(255) NOT NULL,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    stok INTEGER NOT NULL DEFAULT 0,
    stok_minimum INTEGER NOT NULL DEFAULT 5,
    lokasi_penyimpanan VARCHAR(255),
    kondisi_barang VARCHAR(20) NOT NULL DEFAULT 'baik'
        CHECK (kondisi_barang IN ('baik', 'rusak_ringan', 'rusak_berat')),
    gambar VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX products_nama_barang_index ON products (nama_barang);

-- ---------------------------------------------------------------------
-- Tabel: borrowings
-- ---------------------------------------------------------------------
CREATE TABLE borrowings (
    id BIGSERIAL PRIMARY KEY,
    kode_peminjaman VARCHAR(255) NOT NULL UNIQUE,
    nama_peminjam VARCHAR(255) NOT NULL,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali_rencana DATE,
    tanggal_kembali DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'pending'
        CHECK (status IN ('pending', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak')),
    catatan TEXT,
    approved_by BIGINT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: borrowing_details
-- ---------------------------------------------------------------------
CREATE TABLE borrowing_details (
    id BIGSERIAL PRIMARY KEY,
    borrowing_id BIGINT NOT NULL REFERENCES borrowings(id) ON DELETE CASCADE,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    jumlah INTEGER NOT NULL DEFAULT 1,
    kondisi_saat_kembali VARCHAR(20)
        CHECK (kondisi_saat_kembali IN ('baik', 'rusak_ringan', 'rusak_berat')),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- ---------------------------------------------------------------------
-- Tabel: personal_access_tokens (Sanctum, untuk REST API)
-- ---------------------------------------------------------------------
CREATE TABLE personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT,
    last_used_at TIMESTAMP(0) WITHOUT TIME ZONE,
    expires_at TIMESTAMP(0) WITHOUT TIME ZONE,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX personal_access_tokens_tokenable_index ON personal_access_tokens (tokenable_type, tokenable_id);

-- =====================================================================
-- DATA AWAL (SEED)
-- =====================================================================

-- Roles
INSERT INTO roles (name, label, created_at, updated_at) VALUES
    ('admin', 'Administrator', now(), now()),
    ('staff', 'Staff Gudang', now(), now()),
    ('manager', 'Manager', now(), now());

-- Users (password untuk ketiganya: "password")
INSERT INTO users (name, email, email_verified_at, password, role_id, created_at, updated_at) VALUES
    ('Admin Utama', 'admin@inventaris.test', now(),
        '$2b$10$EzaUkz./oW7SgTD3790CGOkbalN/.JMf0okmemlhQKj0Wm9D46Gw.',
        (SELECT id FROM roles WHERE name = 'admin'), now(), now()),
    ('Budi Staff Gudang', 'staff@inventaris.test', now(),
        '$2b$10$EzaUkz./oW7SgTD3790CGOkbalN/.JMf0okmemlhQKj0Wm9D46Gw.',
        (SELECT id FROM roles WHERE name = 'staff'), now(), now()),
    ('Siti Manager', 'manager@inventaris.test', now(),
        '$2b$10$EzaUkz./oW7SgTD3790CGOkbalN/.JMf0okmemlhQKj0Wm9D46Gw.',
        (SELECT id FROM roles WHERE name = 'manager'), now(), now());

-- Categories
INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES
    ('Elektronik', 'elektronik', 'Perangkat elektronik dan gadget kantor', now(), now()),
    ('Furniture', 'furniture', 'Meja, kursi, dan perabot kantor', now(), now()),
    ('Alat Tulis Kantor', 'alat-tulis-kantor', 'ATK dan perlengkapan administrasi', now(), now()),
    ('Peralatan Jaringan', 'peralatan-jaringan', 'Router, switch, kabel, dan aksesori jaringan', now(), now()),
    ('Kendaraan Operasional', 'kendaraan-operasional', 'Kendaraan dinas dan operasional', now(), now());

-- Products
INSERT INTO products (kode_barang, nama_barang, category_id, stok, stok_minimum, lokasi_penyimpanan, kondisi_barang, created_at, updated_at) VALUES
    ('ELK-001', 'Laptop Dell Latitude 5420', (SELECT id FROM categories WHERE name='Elektronik'), 12, 3, 'Gudang IT Lt. 2', 'baik', now(), now()),
    ('ELK-002', 'Proyektor Epson EB-X500', (SELECT id FROM categories WHERE name='Elektronik'), 4, 2, 'Ruang Meeting Lt. 3', 'baik', now(), now()),
    ('ELK-003', 'Kamera Digital Sony A6000', (SELECT id FROM categories WHERE name='Elektronik'), 2, 2, 'Gudang Media Lt. 1', 'baik', now(), now()),
    ('FUR-001', 'Kursi Kantor Ergonomis', (SELECT id FROM categories WHERE name='Furniture'), 25, 5, 'Gudang Umum Lt. 1', 'baik', now(), now()),
    ('FUR-002', 'Meja Lipat Portable', (SELECT id FROM categories WHERE name='Furniture'), 8, 3, 'Gudang Umum Lt. 1', 'rusak_ringan', now(), now()),
    ('ATK-001', 'Proyektor Portable Mini', (SELECT id FROM categories WHERE name='Alat Tulis Kantor'), 3, 2, 'Gudang ATK Lt. 1', 'baik', now(), now()),
    ('ATK-002', 'Whiteboard Magnetik 120x90', (SELECT id FROM categories WHERE name='Alat Tulis Kantor'), 6, 2, 'Gudang ATK Lt. 1', 'baik', now(), now()),
    ('JAR-001', 'Router MikroTik RB750', (SELECT id FROM categories WHERE name='Peralatan Jaringan'), 5, 2, 'Server Room Lt. 4', 'baik', now(), now()),
    ('JAR-002', 'Switch Hub 24 Port', (SELECT id FROM categories WHERE name='Peralatan Jaringan'), 1, 2, 'Server Room Lt. 4', 'baik', now(), now()),
    ('KND-001', 'Motor Operasional Honda Beat', (SELECT id FROM categories WHERE name='Kendaraan Operasional'), 3, 1, 'Parkiran Basement', 'baik', now(), now());

-- Borrowings (contoh data)
INSERT INTO borrowings (kode_peminjaman, nama_peminjam, user_id, tanggal_pinjam, tanggal_kembali_rencana, tanggal_kembali, status, approved_by, created_at, updated_at) VALUES
    ('PJM-0001', 'Budi Staff Gudang', (SELECT id FROM users WHERE email='staff@inventaris.test'),
        CURRENT_DATE - INTERVAL '10 days', CURRENT_DATE - INTERVAL '3 days', CURRENT_DATE - INTERVAL '3 days',
        'dikembalikan', (SELECT id FROM users WHERE email='admin@inventaris.test'), now(), now()),
    ('PJM-0002', 'Budi Staff Gudang', (SELECT id FROM users WHERE email='staff@inventaris.test'),
        CURRENT_DATE - INTERVAL '2 days', CURRENT_DATE + INTERVAL '5 days', NULL,
        'dipinjam', (SELECT id FROM users WHERE email='admin@inventaris.test'), now(), now()),
    ('PJM-0003', 'Budi Staff Gudang', (SELECT id FROM users WHERE email='staff@inventaris.test'),
        CURRENT_DATE, CURRENT_DATE + INTERVAL '3 days', NULL,
        'pending', NULL, now(), now());

-- Borrowing details
INSERT INTO borrowing_details (borrowing_id, product_id, jumlah, kondisi_saat_kembali, created_at, updated_at) VALUES
    ((SELECT id FROM borrowings WHERE kode_peminjaman='PJM-0001'), (SELECT id FROM products WHERE kode_barang='ELK-001'), 1, 'baik', now(), now()),
    ((SELECT id FROM borrowings WHERE kode_peminjaman='PJM-0002'), (SELECT id FROM products WHERE kode_barang='ELK-002'), 1, NULL, now(), now());

COMMIT;
