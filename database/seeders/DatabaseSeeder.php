<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@taspen.co.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'ruri@taspen.local'],
            [
                'name' => 'Ruri',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'rishwal@taspen.local'],
            [
                'name' => 'Rishwal',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        // Seed Categories
        $categories = ['ATK', 'Elektronik', 'Perlengkapan Kantor', 'Cetakan/Form'];
        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(['name' => $category]);
        }

        // Seed Units
        $units = [
            ['name' => 'Pieces', 'short_name' => 'PCS'], 
            ['name' => 'Box', 'short_name' => 'BOX'], 
            ['name' => 'Rim', 'short_name' => 'RIM'], 
            ['name' => 'Pax', 'short_name' => 'PAX'], 
            ['name' => 'Unit', 'short_name' => 'UNIT']
        ];
        foreach ($units as $unit) {
            \App\Models\Unit::firstOrCreate(['short_name' => $unit['short_name']], $unit);
        }

        // Seed Locations
        $locations = ['Gudang Utama', 'Gudang Belakang', 'Ruang Server', 'Ruang Rapat'];
        foreach ($locations as $location) {
            \App\Models\Location::firstOrCreate(['name' => $location]);
        }

        // Fetch IDs
        $atkId = \App\Models\Category::where('name', 'ATK')->first()->id;
        $cetakanId = \App\Models\Category::where('name', 'Cetakan/Form')->first()->id;
        $elektronikId = \App\Models\Category::where('name', 'Elektronik')->first()->id;
        
        $pcsId = \App\Models\Unit::where('short_name', 'PCS')->first()->id;
        $rimId = \App\Models\Unit::where('short_name', 'RIM')->first()->id;
        $unitId = \App\Models\Unit::where('short_name', 'UNIT')->first()->id;

        $gudangUtamaId = \App\Models\Location::where('name', 'Gudang Utama')->first()->id;
        $ruangServerId = \App\Models\Location::where('name', 'Ruang Server')->first()->id;

        // Seed Items (Persediaan)
        $items = [
            ['code' => 'BRG-001', 'name' => 'MAP DOSIR', 'category_id' => $atkId, 'unit_id' => $pcsId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 200, 'minimum_stock' => 20],
            ['code' => 'BRG-002', 'name' => 'KERTAS A4', 'category_id' => $atkId, 'unit_id' => $rimId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 50, 'minimum_stock' => 10],
            ['code' => 'BRG-003', 'name' => 'PULPEN', 'category_id' => $atkId, 'unit_id' => $pcsId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 100, 'minimum_stock' => 15],
            ['code' => 'BRG-004', 'name' => 'AMPLOP', 'category_id' => $atkId, 'unit_id' => $pcsId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 500, 'minimum_stock' => 100],
            ['code' => 'BRG-005', 'name' => 'TINTA PRINTER', 'category_id' => $atkId, 'unit_id' => $pcsId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 10, 'minimum_stock' => 2],
            ['code' => 'BRG-006', 'name' => 'FORM', 'category_id' => $cetakanId, 'unit_id' => $pcsId, 'location_id' => $gudangUtamaId, 'type' => 'non_inventory', 'current_stock' => 1000, 'minimum_stock' => 100],
        ];

        foreach ($items as $item) {
            \App\Models\Item::firstOrCreate(['code' => $item['code']], $item);
        }

        // Seed Master Items for Assets
        $assetItems = [
            ['code' => 'AST-PC', 'name' => 'PERSONAL COMPUTER', 'category_id' => $elektronikId, 'unit_id' => $unitId, 'location_id' => $ruangServerId, 'type' => 'inventory', 'current_stock' => 1, 'minimum_stock' => 0],
            ['code' => 'AST-SCN', 'name' => 'SCANNER', 'category_id' => $elektronikId, 'unit_id' => $unitId, 'location_id' => $ruangServerId, 'type' => 'inventory', 'current_stock' => 1, 'minimum_stock' => 0],
            ['code' => 'AST-PRT', 'name' => 'PRINTER', 'category_id' => $elektronikId, 'unit_id' => $unitId, 'location_id' => $ruangServerId, 'type' => 'inventory', 'current_stock' => 1, 'minimum_stock' => 0],
            ['code' => 'AST-PRJ', 'name' => 'PROYEKTOR', 'category_id' => $elektronikId, 'unit_id' => $unitId, 'location_id' => $ruangServerId, 'type' => 'inventory', 'current_stock' => 1, 'minimum_stock' => 0],
        ];

        foreach ($assetItems as $assetItem) {
            \App\Models\Item::firstOrCreate(['code' => $assetItem['code']], $assetItem);
        }

        // Get Master Asset Item IDs
        $pcId = \App\Models\Item::where('code', 'AST-PC')->first()->id;
        $scnId = \App\Models\Item::where('code', 'AST-SCN')->first()->id;
        $prtId = \App\Models\Item::where('code', 'AST-PRT')->first()->id;
        $prjId = \App\Models\Item::where('code', 'AST-PRJ')->first()->id;

        // Seed Individual Assets
        $assets = [
            ['item_id' => $pcId, 'asset_number' => '402000008291', 'location_id' => $ruangServerId, 'condition' => 'baik', 'status' => 'tersedia', 'notes' => 'DELL Optiplex'],
            ['item_id' => $scnId, 'asset_number' => '402000008292', 'location_id' => $ruangServerId, 'condition' => 'baik', 'status' => 'tersedia', 'notes' => 'Epson L3110'],
            ['item_id' => $prtId, 'asset_number' => '402000008293', 'location_id' => $ruangServerId, 'condition' => 'rusak_ringan', 'status' => 'perbaikan', 'notes' => 'HP LaserJet'],
            ['item_id' => $prjId, 'asset_number' => '402000008294', 'location_id' => $ruangServerId, 'condition' => 'baik', 'status' => 'dipinjam', 'notes' => 'BenQ MX528'],
        ];

        foreach ($assets as $asset) {
            \App\Models\Asset::firstOrCreate(['asset_number' => $asset['asset_number']], $asset);
        }
    }
}
