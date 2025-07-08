@echo off
echo Running database updates...
echo.

echo 1. Adding route columns to bus table...
"D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe" -u root -p travel < update_bus_table.sql

echo.
echo 2. Adding Sulawesi bus routes data...
"D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe" -u root -p travel -e "INSERT INTO bus (nama_po, kelas, kapasitas, rute_awal, rute_akhir) VALUES ('Litha ^& Co', 'Suites Class', 45, 'Makassar', 'Manado'), ('Litha ^& Co', 'Executive', 48, 'Makassar', 'Palu'), ('Litha ^& Co', 'Bisnis', 50, 'Makassar', 'Mamuju'), ('Bintang Prima', 'Executive', 45, 'Makassar', 'Toraja'), ('Bintang Prima', 'Bisnis', 50, 'Makassar', 'Palopo'), ('Bintang Prima', 'Ekonomi', 55, 'Makassar', 'Pare-Pare'), ('Bintang Timur', 'Executive', 40, 'Makassar', 'Watampone'), ('Bintang Timur', 'Bisnis', 45, 'Makassar', 'Sengkang'), ('Borlindo Mandiri Jaya', 'Sleeper', 35, 'Makassar', 'Manado'), ('Borlindo Mandiri Jaya', 'Executive', 42, 'Makassar', 'Gorontalo'), ('Primadona', 'Double Decker', 60, 'Makassar', 'Kendari'), ('Primadona', 'Executive', 45, 'Makassar', 'Palu'), ('Metro Permai', 'Bisnis', 48, 'Makassar', 'Bone'), ('Metro Permai', 'Ekonomi', 52, 'Makassar', 'Bulukumba'), ('Adiputra', 'Executive', 44, 'Manado', 'Tomohon'), ('Alam Indah', 'Bisnis', 46, 'Manado', 'Bitung'), ('Batutomonga', 'Executive', 42, 'Makassar', 'Toraja'), ('Bintang Zahira Trans', 'Bisnis', 48, 'Makassar', 'Sinjai'), ('Cahaya Bone', 'Executive', 40, 'Makassar', 'Watampone'), ('Charisma Transport', 'Bisnis', 50, 'Palu', 'Poso'), ('Garuda', 'Executive', 45, 'Makassar', 'Parepare'), ('Gunung Rejeki', 'Bisnis', 48, 'Makassar', 'Enrekang'), ('Khatulistiwa Trans', 'Executive', 42, 'Makassar', 'Polewali'), ('Liman', 'Bisnis', 46, 'Makassar', 'Majene'), ('Manggala Trans', 'Executive', 44, 'Makassar', 'Gowa'), ('Mega Mas', 'Bisnis', 50, 'Makassar', 'Takalar'), ('Neo Trans', 'Executive', 42, 'Kendari', 'Kolaka'), ('Nurul Kiki', 'Bisnis', 48, 'Kendari', 'Bau-Bau'), ('Piposs', 'Executive', 40, 'Makassar', 'Bantaeng'), ('Putra Jaya', 'Bisnis', 46, 'Makassar', 'Jeneponto'), ('Rajawali Trans', 'Executive', 44, 'Makassar', 'Selayar'), ('Seri Trans', 'Bisnis', 48, 'Palu', 'Donggala'), ('Setuju', 'Executive', 42, 'Makassar', 'Maros'), ('Sinar Muda', 'Bisnis', 50, 'Makassar', 'Pangkep'), ('Sumber Tani', 'Executive', 45, 'Makassar', 'Barru'), ('Tikulembang', 'Bisnis', 46, 'Makassar', 'Pinrang'), ('Tomohon Indah', 'Executive', 40, 'Manado', 'Tomohon'), ('Vernando', 'Bisnis', 48, 'Makassar', 'Wajo'), ('Zafa Trans', 'Executive', 44, 'Makassar', 'Luwu') ON DUPLICATE KEY UPDATE nama_po=VALUES(nama_po);"

echo.
echo Database updates completed!
pause
