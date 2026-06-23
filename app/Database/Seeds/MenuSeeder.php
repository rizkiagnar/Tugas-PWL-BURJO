<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // FOODS (Makanan)
            [
                'name'         => 'Indomie Goreng',
                'type'         => 'makanan',
                'price'        => 10000,
                'image'        => 'indomie_goreng.jpg',
                'description'  => 'Indomie goreng legendaris disajikan dengan telur matang/setengah matang, sawi segar, dan taburan bawang goreng.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Indomie Rebus',
                'type'         => 'makanan',
                'price'        => 10000,
                'image'        => 'indomie_rebus.jpg',
                'description'  => 'Indomie rebus dengan kuah gurih hangat, ditambah potongan cabai rawit, telur, sawi, dan tomat segar.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Nasi Ruwed',
                'type'         => 'makanan',
                'price'        => 13000,
                'image'         => 'nasi_ruwed.jpg',
                'description'  => 'Perpaduan nasi goreng dan mi instan yang dimasak jadi satu dengan bumbu khas burjo yang gurih manis.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Nasi Goreng',
                'type'         => 'makanan',
                'price'        => 12000,
                'image'        => 'nasi_goreng.jpg',
                'description'  => 'Nasi goreng khas aa burjo dengan aroma wajan (wok hei) yang khas, dilengkapi telur orak-arik dan kerupuk.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Nasi Orak Arik',
                'type'         => 'makanan',
                'price'        => 11000,
                'image'        => 'nasi_orak_arik.jpg',
                'description'  => 'Nasi hangat disiram orak-arik telur super lembut dengan irisan sayur kubis, wortel, dan saus gurih.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Mie Dok Dok',
                'type'         => 'makanan',
                'price'        => 14000,
                'image'        => 'mie_dok_dok.jpg',
                'description'  => 'Mi kuah kental manis-gurih-pedas dengan racikan bumbu rahasia burjo, telur bebek/ayam hancur, dan kol segar.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Nasi Magelangan',
                'type'         => 'makanan',
                'price'        => 13000,
                'image'        => 'nasi_magelangan.jpg',
                'description'  => 'Nasi goreng dicampur mie kuning dengan bumbu magelangan manis gurih, disajikan dengan telur ceplok.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],

            // DRINKS (Minuman)
            [
                'name'         => 'Es Teh',
                'type'         => 'minuman',
                'price'        => 3000,
                'image'        => 'esteh.jpg',
                'description'  => 'Es teh manis jumbo super segar dari racikan teh tubruk khas Jawa Tengah.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Es Jeruk',
                'type'         => 'minuman',
                'price'        => 4000,
                'image'        => 'es_jeruk.jpg',
                'description'  => 'Es jeruk peras asli dengan rasa asam manis alami penurun dahaga.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Es Nutrisari',
                'type'         => 'minuman',
                'price'        => 4000,
                'image'        => 'es_nutrisari.jpg',
                'description'  => 'Es Nutrisari jeruk manis dingin segar yang disukai semua kalangan.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Es Teh Tarik',
                'type'         => 'minuman',
                'price'        => 6000,
                'image'        => 'es_teh_tarik.jpg',
                'description'  => 'Teh dicampur susu kental manis yang ditarik hingga berbusa tebal dan legit.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Es Good Day Freeze',
                'type'         => 'minuman',
                'price'        => 5000,
                'image'        => 'es_good_day_freeze.jpg',
                'description'  => 'Es kopi instan Good Day Freeze dengan sensasi dingin mint-mokacino yang menyegarkan.',
                'is_available' => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('menus')->insertBatch($data);
    }
}
