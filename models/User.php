<?php

// File: models/User.php
// Deskripsi: Superclass untuk semua jenis pengguna dalam sistem.

class User {
    // Properti sesuai dengan Diagram Kelas
    protected $id_user;
    protected $username;
    protected $password; // Sebaiknya dalam bentuk hash
    protected $email;
    protected $no_telp;
    protected $role;

    protected $pdo;

    /**
     * Constructor untuk inisialisasi koneksi database.
     * @param PDO $pdo Objek koneksi database.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Metode untuk menangani login pengguna.
     * Implementasi detail akan ditambahkan saat membuat controller login.
     * 
     * @param string $usernameOrEmail
     * @param string $password
     * @return bool True jika login berhasil, false jika gagal.
     */
    public function login($usernameOrEmail, $password) {
        // 1. Cari pengguna berdasarkan username atau email
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch();

        // 2. Jika pengguna ditemukan, verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            // 3. Jika password cocok, kembalikan data pengguna
            // Hapus password dari array sebelum mengembalikan untuk keamanan
            unset($user['password']);
            return $user;
        }

        // 4. Jika pengguna tidak ditemukan atau password salah, kembalikan false
        return false;
    }



    // Getter dan Setter untuk properti jika diperlukan
    public function getIdUser() {
        return $this->id_user;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

     public function getRole() {
        return $this->role;
    }

    /**
     * Menghitung jumlah total pengguna terdaftar.
     *
     * @return int Jumlah total pengguna.
     */
    public function getTotalCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Mengambil semua pengguna dari database.
     * @return array Daftar semua pengguna.
     */
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT u.id_user, u.username, u.email, u.role, u.no_telp, u.created_at,
                   pd.alamat
            FROM users u
            LEFT JOIN pelanggan_details pd ON u.id_user = pd.id_user
            ORDER BY u.username ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mencari pengguna berdasarkan ID.
     * @param int $id_user ID pengguna.
     * @return mixed Data pengguna jika ditemukan, false jika tidak.
     */
    public function findById($id_user) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id_user = ?");
        $stmt->execute([$id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menyimpan pengguna baru ke database.
     * @param array $data Data pengguna.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function store($data) {
        $sql = "INSERT INTO users (username, email, password, role, no_telp, alamat) VALUES (?, ?, ?, ?, ?, ?)";
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['username'],
                $data['email'],
                $password_hash,
                $data['role'],
                $data['no_telp'],
                $data['alamat']
            ]);
        } catch (PDOException $e) {
            // Kemungkinan username atau email duplikat
            return false;
        }
    }

    /**
     * Memperbarui data pengguna di database.
     * @param int $id_user ID pengguna.
     * @param array $data Data pengguna yang akan diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update($id_user, $data) {
        // Jika password tidak kosong, sertakan dalam query update
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ?, no_telp = ?, alamat = ? WHERE id_user = ?";
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $params = [$data['username'], $data['email'], $password_hash, $data['role'], $data['no_telp'], $data['alamat'], $id_user];
        } else {
            // Jika password kosong, jangan update password
            $sql = "UPDATE users SET username = ?, email = ?, role = ?, no_telp = ?, alamat = ? WHERE id_user = ?";
            $params = [$data['username'], $data['email'], $data['role'], $data['no_telp'], $data['alamat'], $id_user];
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Menghapus pengguna dari database.
     * @param int $id_user ID pengguna.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete($id_user) {
        // Sebaiknya tambahkan pengecekan agar admin tidak bisa menghapus dirinya sendiri
        if (isset($_SESSION['user_id']) && $id_user == $_SESSION['user_id']) {
            return false;
        }

        $sql = "DELETE FROM users WHERE id_user = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_user]);
        } catch (PDOException $e) {
            // Gagal menghapus karena foreign key constraint, dll.
            return false;
        }
    }
}

?>
