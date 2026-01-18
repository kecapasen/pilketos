<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VoterSeeder extends Seeder
{
    public function run()
    {
        // Data siswa per kelas
        $classes = [
            'X BOGA' => [
                'ABDUL GHAFFAR JAZILUL FAWAID HADIAMARTA',
                'ABDURAHMAN DONI',
                'ATHIFA NAYLA PUTRI',
                'DELLISTYA SALSABILA MUZAHRA',
                'DZAKYA TALITA SAKHI',
                'FIKHA DESTYA SOPIANA HAMZAH',
                'MAULINA SEPTIANI',
                'MUHAMMAD VIKY DAFFIYANTO',
                'NASYWA HANIFAH',
                'NUR AULIA NINGSIH BIMA PUTRI',
                'QISMIKA NURUL ITRAH',
                'REVAL ARIVANSYAH',
            ],
            'X RPL' => [
                'ANURSIFA',
                'ARSYA AULIA HALIMATUSYA\'DIAH',
                'AVISENA ARDIAN',
                'BABY ALECYA',
                'DEWI ANJANI',
                'DIANA YUNITA ISKANDAR',
                'FATIHA JAHAUROTUL MUNA',
                'GALIH KSATRIA BHAKTI',
                'HENI SURYANI',
                'HIRFA FAJARINA JUMADITS TSARIYYAH',
                'IRMA INAYAH',
                'JENITA NUR MAYSIE',
                'KUSTIAN NUGRAHA',
                'MUHAMMAD DZAKIYY AL-UQAFFI SEMERU',
                'MUHAMMAD FARIDZ',
                'NI LUH KOMANG DEVINA ADRIENE SUMERTHA',
                'NISRINA',
                'RESTU RIQKI ZEISMAN',
                'RISQIA FEBRIANI PUTRI',
                'SATRIO WIJDAN PRATAMA',
                'SITI HALIMAH',
                'SOPIYATU SOLEHA',
                'TRISNIA SIFA NINGSIH',
                'ZALFA LUTHFIA YULIANTI',
            ],
            'X TKR' => [
                'ADITYA NUGROHO',
                'AHMAD RIFQI ASSIDQI',
                'AKBAR FADILAH',
                'ALIF FATKHUR ROHMAN',
                'ANDIKA DWI PUTRA',
                'AZKA ABDALFAWWAZ SUPRAPTO',
                'DAVID VIAN AL HUSEN',
                'DELIAN FISESA',
                'DHEA RIZKY AMALIA',
                'EVANIA ABDAD',
                'EZA PADILA',
                'FAIQ ALAMSYAH',
                'FARIS AZIZILLAH',
                'FIKRY AL MUHAIMIN',
                'GIBRAN PRAJA MAULANA',
                'HERMALA YUSANTI',
                'LUKMAN HAKIM',
                'MOCHAMAD EZAR FARROZ ATHAILLAH',
                'MOCHAMMAD ARSYIEL AZZIRWAN',
                'MOHAMMED HIDAYAT',
                'MUHAMAD FAHRY IBRAHIM',
                'MUHAMAD HABIBURAHMAN',
                'MUHAMMAD ALIF AL KHUDRI',
                'MUHAMMAD ARIF NIZAR',
                'MUHAMMAD FAHRI SAPUTRA',
                'MUHAMMAD FATHIR ALFARIDZI',
                'MUHAMMAD RIZKY ALFIANSYAH',
                'MUHAMMAD RIZQI FITRIANTO',
                'RADITHYA SIDQIE PRADANA',
                'REIHAN PRASETIA',
                'RIDO MUSTOVA',
                'RIZKI SETIAWAN',
                'RIZKY ADITIA',
                'SANDI HENDRIAWAN',
                'SATRIO NOVALINO PUTRA',
                'SRI DEVI',
                'TEDI PERMANA',
                'YUDHANUGRAHA',
            ],
            'X TMI' => [
                'AKBAR REFANA YUNANDAR',
                'ALFIAN NUR AQIL',
                'ARDAN ARDIANSYAH',
                'ARIB NUR SAKTI',
                'DUDE HERLANGGA',
                'FADLAN SYAPUTRA',
                'FAIQ ADNAN FATIHHUDIN',
                'FIKRI MAULANA RAZIQ',
                'KAKAN SAKA ANGKONANDO',
                'MUHAMMAD ARDAN PRAYOGA',
                'MUHAMMAD WISNU ALKATIRI',
                'REZKYANDA FEBRIANSYAH',
                'RIZKI KHAERUDIN',
                'RIZKY ADITYA PRATAMA',
                'SATRIO BUDI ARIFIANTO',
                'YASINTA AULIA DEWI',
            ],
            'XI BOGA' => [
                'ADELIA LAILANI NADINE',
                'AISYA HANAFI',
                'AISYAH',
                'ASYIFA DESWITA SARI',
                'FANNIA OKTAVIA',
                'IRSYA PUTRI TOTIANI',
                'KEYSHA PUTRI NURHAQI',
                'MELINDA ALFATIANI',
                'RAHMAWATI PUTRI DEWI',
                'SILVIA NOVITA SARI',
            ],
            'XI RPL' => [
                'ALINDA',
                'AMELIA SAFITRI',
                'ARJUNA KAINAN IBRAHIM AL KHALIFI',
                'EKA MAY CERLY PUTRI',
                'FAWWAZ FATHURRAHMAN',
                'IYAS ANASAZARIF YUSUF',
                'KARISA AURELIA SANTOSO',
                'KEISA NISA NUR ANNASR',
                'MUHAMMAD MIFTAH FAUZI',
                'MUHAMMAD ZUFAR ISMAIL',
                'NAYDA ZAHRAN SABITAH',
                'NUR AINI YASAARI',
                'SAFIRA RAMADANIA',
                'SHINTA TIARA',
                'SITI NURAISYAH',
                'SUKMA AYU LESTARI',
            ],
            'XI TKR' => [
                'AHMAD PRAMUDIYA NUR ISMAIL',
                'ANDIKA CIPTA',
                'CENDI FERNAN AL FARIZ',
                'EKA BIMAKLARIAN',
                'GALIH ADITYA PRATAMA',
                'GALIH PERMANA',
                'ILHAM APRIYANSAH',
                'ISNAN ALI HASIBUAN',
                'MOCHAMMAD HAYKAL ALFISYAHRIN',
                'MUHAMMAD RAKHA BAIHAQI',
                'RAIHAN LINTANG MAHARDIKA',
                'REZA DHARMAWAN',
                'RIZQY SAPUTRA',
            ],
            'XI TMI' => [
                'ANUGRAH RAHAYU',
                'FARIZ ALIF FEBRIYAN',
                'MARCEL DANANG PRAYOGA',
                'MELANI WIJAYA PUTRI',
                'MUHAMMAD FIKRI FAUZIA',
                'NADIA NURCAHYA SAFITRI',
                'REHAN',
                'WIDYA',
            ],
            'XII BOGA' => [
                'A\'ATHIFAH PUTRI MAULIDYA',
                'ADINDA PUTRI MARSANTO',
                'ANNISA FARAH ZETTA',
                'AURELIO AMINUL KARIIM',
                'DEA AISHA SALEH',
                'MEINIRA AZZAHRAWANY',
                'RAFFI ALHARIRI',
                'RANI MEISYA AGUSTIN',
                'RIVANI MAULIDA',
                'SATRIA KALINGGA YUDISTIRA',
                'SERINA AULIA',
                'SERINA MAULINA',
            ],
            'XII RPL' => [
                'ADZRA LUTHFIYYAH RAMADHANI',
                'ALFIKNA PUTRI TAULADANI',
                'ASYAM SAJID',
                'AWFIYAH PUSPITA INDAH',
                'BUNGA AIMAH',
                'CHERIL AZIZAH QINTHARA',
                'DEBRI MARWANDHA HAKIM HARTONO',
                'FIBRI FERNANDA KUSUMA',
                'HABIB MAULANA',
                'KHAILA DWI JULIANTI',
                'MAYA APRILIANZA',
                'MUHAMAD RAFA ZULFANAYA',
                'MUHAMMAD RIDHO AL AKBAR',
                'NADIA FITRI',
                'NADYNE AULIA AZZAHRA',
                'NURUN WAKHIDATUN ASYIAH',
                'PUTRI ANGGUN RAMADHANI',
                'RIZKY MAULANA',
                'SANDRA LEONY AGUSTIARAYA',
                'SANDY PUTRA EKA GUNAFEL',
                'SENDY PUTRA DWI GUNAFEL',
                'SITI KHOIRUNNISA',
                'SITI PATIMAH',
                'SYIFA NADIA KHOIRUNNISA',
                'ULFA UMAYAH',
            ],
            'XII TKR' => [
                'ABDUL MALIK BAADILLAH',
                'ACHMAD RADITIYANSYAH',
                'AHMAD SYARIFUDIN WAHID',
                'ANGGIA AZWA TYASSUCI',
                'FAJAR FATURROHMAN',
                'MUHAMAD HASYIMI',
                'MUHAMMAD FALLENT ALZENA',
                'MUHAMMAD REZKY JANUAR',
                'RAFI DAVA MAULANA',
                'RIJA HAITSAM HADZIQ',
                'SANDY SYA\'BANA',
                'VERAWATI',
            ],
            'XII TMI' => [
                'BERIL SALWA HAERULLOH',
                'GHAISAN DANISH KAMALUDDIN',
                'JIDAN RIDHO JAQIA KHOLBI',
            ],
        ];

        $data = [];
        foreach ($classes as $classGroup => $students) {
            foreach ($students as $name) {
                $data[] = [
                    'name'        => $name,
                    'class_group' => $classGroup,
                    'token'       => $this->generateToken(),
                    'status'      => 'not_voted',
                    'created_at'  => date('Y-m-d H:i:s'),
                ];
            }
        }

        // Hapus data lama (disable FK check dulu)
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('votes')->truncate();
        $this->db->table('voters')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        echo "🗑️ Data lama berhasil dihapus\n";
        
        // Insert ke database
        $this->db->table('voters')->insertBatch($data);
        
        echo "✅ Berhasil menambahkan " . count($data) . " pemilih\n";
    }

    /**
     * Generate random token 6 karakter (huruf besar + angka)
     */
    private function generateToken(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $token;
    }
}
