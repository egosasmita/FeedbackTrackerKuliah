<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabasePsdkuSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123456');

        // 1. DATA ROLE
        $roles = ['Admin', 'Dosen', 'Mahasiswa'];
        foreach ($roles as $role) {
            DB::table('role')->updateOrInsert(['nama_role' => $role], ['nama_role' => $role]);
        }
        $idRoleAdmin = DB::table('role')->where('nama_role', 'Admin')->value('id_role');
        $idRoleDosen = DB::table('role')->where('nama_role', 'Dosen')->value('id_role');
        $idRoleMhs   = DB::table('role')->where('nama_role', 'Mahasiswa')->value('id_role');

        // 2. DATA PROGRAM STUDI
        $prodiList = ['Manajemen Informatika', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi'];
        foreach ($prodiList as $p) {
            DB::table('program_studi')->updateOrInsert(['nama_prodi' => $p], ['nama_prodi' => $p]);
        }
        $idProdiMI = DB::table('program_studi')->where('nama_prodi', 'Manajemen Informatika')->value('id_prodi');

        // 3. DATA KELAS (MI 2B)
        $idKelas2B = DB::table('kelas')->insertGetId([
            'id_prodi' => $idProdiMI,
            'nama_kelas' => 'MI 2B',
            'angkatan' => 2023,
            'created_at' => now(),
        ]);

        // 4. DATA SKALA PENILAIAN
        $skala = [
            ['nilai' => 1, 'keterangan' => 'Tidak Paham'],
            ['nilai' => 2, 'keterangan' => 'Kurang Paham'],
            ['nilai' => 3, 'keterangan' => 'Cukup Paham'],
            ['nilai' => 4, 'keterangan' => 'Paham'],
            ['nilai' => 5, 'keterangan' => 'Sangat Paham'],
        ];
        foreach ($skala as $s) {
            DB::table('skala_penilaian')->updateOrInsert(['nilai' => $s['nilai']], $s);
        }

        // 5. DATA USER ADMIN
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@psdku.polinema.ac.id'],
            [
                'id_role' => $idRoleAdmin,
                'nama' => 'Admin PSDKU Kediri',
                'password' => $password,
                'status_aktif' => true,
                'created_at' => now(),
            ]
        );

        // 6. DATA DOSEN (Dengan Gelar & NIDN Manual)
        $dataDosen = [
            ['nama' => 'Fery Sofian Efendi, S.Kom., M.Cs.', 'email' => 'fery.s@polinema.ac.id', 'nip' => '0019048501'],
            ['nama' => 'Toga Aldila Cinderatama, S.ST., M.Sc.', 'email' => 'toga.a@polinema.ac.id', 'nip' => '0021059402'],
            ['nama' => 'Ratna Widyastuti, S.Si., M.Si.', 'email' => 'ratna.w@polinema.ac.id', 'nip' => '0008077603'],
            ['nama' => 'Afta Rahma, S.Pd., M.Pd.', 'email' => 'afta.r@polinema.ac.id', 'nip' => '0011059204'],
            ['nama' => 'Abidatul Muqoddasah, S.Kom., M.Kom.', 'email' => 'abidatul@polinema.ac.id', 'nip' => '0020018805'],
            ['nama' => 'Fadelis Nurul Huda, S.Kom., M.Kom.', 'email' => 'fadelis@polinema.ac.id', 'nip' => '0019099006'],
            ['nama' => 'Benny Setiawan, S.Kom., M.Kom.', 'email' => 'benny@polinema.ac.id', 'nip' => '0018018707'],
            ['nama' => 'Badrus Zaman, S.Pd., M.Pd.', 'email' => 'badrus@polinema.ac.id', 'nip' => '0009128008'],
        ];

        $dosenIds = [];
        foreach ($dataDosen as $d) {
            $uId = DB::table('users')->insertGetId([
                'id_role' => $idRoleDosen,
                'nama' => $d['nama'],
                'email' => $d['email'],
                'password' => $password,
                'created_at' => now(),
            ]);
            $dId = DB::table('dosen')->insertGetId([
                'id_user' => $uId,
                'nip' => $d['nip'], // NIDN yang kamu berikan
                'created_at' => now(),
            ]);
            $dosenIds[$d['nama']] = $dId;
        }

        // 7. DATA MAHASISWA (MI 2B)
        $mhsList = [
            ['nim' => '243107030039', 'nama' => 'AHMAD TEGUH DONY SETIAWAN'],
            ['nim' => '243107030053', 'nama' => 'ANINDA DWI DARMAYANTI'],
            ['nim' => '243107030084', 'nama' => 'AYUDYA DIAH HAPSARI'],
            ['nim' => '243107030025', 'nama' => 'BIMA RAMADHANI'],
            ['nim' => '243107030076', 'nama' => 'DERA AMANDA PUTRI SULISTIONO'],
            ['nim' => '243107030132', 'nama' => 'EGO SASMITA'],
            ['nim' => '243107030119', 'nama' => 'ERA FAZIRA'],
            ['nim' => '243107030052', 'nama' => 'GALUH KUSUMA WARDHANI'],
            ['nim' => '243107030089', 'nama' => 'ISNA ANA ANGGER ALIF ALIA'],
            ['nim' => '243107030081', 'nama' => 'KRISNA BAGUS KINTARO'],
            ['nim' => '243107030017', 'nama' => 'LINTANG NASYWA SHOFA MAHARANI'],
            ['nim' => '243107030131', 'nama' => 'M. YOGA INDRA FATA ASYIQI'],
            ['nim' => '243107030012', 'nama' => 'MOHAMMAD RIZKY ALI IMAADUDDIN'],
            ['nim' => '243107030032', 'nama' => 'NICOLLA JUAN ARDHAN'],
            ['nim' => '243107030087', 'nama' => 'NOVA AULIYATUL FAIZAH'],
            ['nim' => '243107030018', 'nama' => 'RAISSA AGNESIA NAJWAH'],
            ['nim' => '243107030057', 'nama' => 'RAYHAN NDARU NOOR RABBANI'],
            ['nim' => '243107031016', 'nama' => 'RIZKY EKO WAHYU PURNOMO'],
            ['nim' => '243107030028', 'nama' => 'SEINDY HANUNG PRATAMA'],
            ['nim' => '243107030083', 'nama' => 'SILVIA ZAHRANI FIRDAUS'],
            ['nim' => '243107030046', 'nama' => 'WAHYUNI AISYA NUGRAHA'],
        ];

        foreach ($mhsList as $mhs) {
            $uId = DB::table('users')->insertGetId([
                'id_role' => $idRoleMhs,
                'nama' => $mhs['nama'],
                'email' => $mhs['nim'] . '@student.polinema.ac.id',
                'password' => $password,
                'created_at' => now(),
            ]);
            DB::table('mahasiswa')->insert([
                'id_user' => $uId,
                'id_prodi' => $idProdiMI,
                'id_kelas' => $idKelas2B,
                'nim' => $mhs['nim'],
                'angkatan' => 2024,
                'created_at' => now(),
            ]);
        }

        // 8. DATA MATA KULIAH (MI Semester 4)
        $matkulData = [
            ['nama' => 'Proyek Sistem Informasi', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Manajemen Jaringan Komputer', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Statistik Probabilitas', 'sem' => 4, 'sks' => 2],
            ['nama' => 'Sistem IoT Terintegrasi', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Data Science', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Sistem Manajemen Basdat', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Pemrograman Mobile', 'sem' => 4, 'sks' => 3],
            ['nama' => 'Bahasa Indonesia', 'sem' => 4, 'sks' => 2],
        ];

        $matkulIds = [];
        foreach ($matkulData as $m) {
            $mId = DB::table('mata_kuliah')->insertGetId([
                'id_prodi' => $idProdiMI,
                'nama_matkul' => $m['nama'],
                'semester' => $m['sem'],
                'sks' => $m['sks']
            ]);
            $matkulIds[$m['nama']] = $mId;
        }

        // 9. DATA JADWAL (Hubungkan Mata Kuliah dengan Nama Dosen yang sudah pakai gelar)
        $jadwalData = [
            ['matkul' => 'Proyek Sistem Informasi', 'dosen' => 'Fery Sofian Efendi, S.Kom., M.Cs.', 'hari' => 'Senin', 'mulai' => '07:00', 'selesai' => '12:10'],
            ['matkul' => 'Manajemen Jaringan Komputer', 'dosen' => 'Toga Aldila Cinderatama, S.ST., M.Sc.', 'hari' => 'Selasa', 'mulai' => '07:00', 'selesai' => '11:20'],
            ['matkul' => 'Statistik Probabilitas', 'dosen' => 'Ratna Widyastuti, S.Si., M.Si.', 'hari' => 'Selasa', 'mulai' => '11:20', 'selesai' => '14:30'],
            ['matkul' => 'Sistem IoT Terintegrasi', 'dosen' => 'Afta Rahma, S.Pd., M.Pd.', 'hari' => 'Rabu', 'mulai' => '07:00', 'selesai' => '11:20'],
            ['matkul' => 'Data Science', 'dosen' => 'Abidatul Muqoddasah, S.Kom., M.Kom.', 'hari' => 'Kamis', 'mulai' => '07:00', 'selesai' => '11:20'],
            ['matkul' => 'Sistem Manajemen Basdat', 'dosen' => 'Fadelis Nurul Huda, S.Kom., M.Kom.', 'hari' => 'Kamis', 'mulai' => '11:20', 'selesai' => '17:10'],
            ['matkul' => 'Pemrograman Mobile', 'dosen' => 'Benny Setiawan, S.Kom., M.Kom.', 'hari' => 'Jum\'at', 'mulai' => '07:00', 'selesai' => '13:40'],
            ['matkul' => 'Bahasa Indonesia', 'dosen' => 'Badrus Zaman, S.Pd., M.Pd.', 'hari' => 'Jum\'at', 'mulai' => '13:40', 'selesai' => '15:20'],
        ];

        foreach ($jadwalData as $j) {
            DB::table('jadwal')->insert([
                'id_matkul' => $matkulIds[$j['matkul']],
                'id_dosen' => $dosenIds[$j['dosen']],
                'id_kelas' => $idKelas2B,
                'hari' => $j['hari'],
                'jam_mulai' => $j['mulai'],
                'jam_selesai' => $j['selesai'],
                'tahun_ajaran' => '2024/2025',
                'created_at' => now(),
            ]);
        }

        $this->command->info('Update Seeder: Nama Dosen Pakai Gelar & NIDN Sukses!');
    }
}