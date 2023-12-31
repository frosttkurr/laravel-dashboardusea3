<?php

namespace Database\Seeders;

use App\Models\JenisBiota;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role',
            'user',
            'biota',
            'jenis-biota',
            'jenis-temuan',
            'kondisi-perairan',
            'laporan-nelayan',
            'lokasi',
            'track',
            'lihat-report-biota',
            'lihat-kondisi-perairan',
            'logs',
            'sig',
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }

         $user = User::create([
            'name' => 'Admin', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'avatar' => '1637834357.jpg'
        ]);
        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::where(function ($query) {
                                    $query->where('name', 'biota')
                                    ->orWhere('name', 'jenis-biota')
                                    ->orWhere('name', 'kondisi-perairan')
                                    ->orWhere('name', 'track')
                                    ->orWhere('name', 'jenis-biota')
                                    ->orWhere('name', 'jenis-temuan')
                                    ->orWhere('name', 'lokasi')
                                    ->orWhere('name', 'laporan-nelayan')
                                    ->orWhere('name', 'role')
                                    ->orWhere('name', 'user')
                                    ->orWhere('name', 'logs')
                                    ->orWhere('name', 'sig');
                                })->pluck('id','id');
   
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        $user3 = User::create([
            'name' => 'Team U-Fish', 
            'email' => 'team-ufish@gmail.com',
            'password' => bcrypt('123456'),
            'avatar' => '1637834357.jpg'
        ]);
        $role3 = Role::create(['name' => 'Team U-Fish']);
        $permissions3 = Permission::where(function ($query) {
                                    $query->where('name', 'biota')
                                    ->orWhere('name', 'jenis-biota')
                                    ->orWhere('name', 'kondisi-perairan')
                                    ->orWhere('name', 'track')
                                    ->orWhere('name', 'jenis-biota')
                                    ->orWhere('name', 'jenis-temuan')
                                    ->orWhere('name', 'lokasi')
                                    ->orWhere('name', 'laporan-nelayan')
                                    ->orWhere('name', 'sig');
                                })->pluck('id','id');
        $role3->syncPermissions($permissions3);
        $user3->assignRole([$role3->id]);

        $user4 = User::create([
            'name' => 'Time', 
            'email' => 'time@gmail.com',
            'password' => bcrypt('123456'),
            'avatar' => '1637834357.jpg'
        ]);
        $role4 = Role::create(['name' => 'Time']);
        $permissions4 = Permission::where(function ($query) {
                                                $query->where('name', 'biota')
                                                ->orWhere('name', 'jenis-biota');
                                            })->pluck('id','id');
        $role4->syncPermissions($permissions4);
        $user4->assignRole([$role4->id]);

        Lokasi::insert([
            ['nama_lokasi' => 'Kabupaten Badung'],
            ['nama_lokasi' => 'Kabupaten Bangli '],
            ['nama_lokasi' => 'Kabupaten Buleleng'],
            ['nama_lokasi' => 'Kabupaten Gianyar'],
            ['nama_lokasi' => 'Kabupaten Jembrana'],
            ['nama_lokasi' => 'Kabupaten Karangasem'],
            ['nama_lokasi' => 'Kabupaten Klungkung'],
            ['nama_lokasi' => 'Kabupaten Tabanan'],
            ['nama_lokasi' => 'Kota Denpasar'],
        ]);

        JenisBiota::insert([
            ['jenis_biota' => 'Ikan'],
            ['jenis_biota' => 'Molluska'],
            ['jenis_biota' => 'Kepiting'],
            ['jenis_biota' => 'Penyu'],
            ['jenis_biota' => 'Ubur-ubur'],
            ['jenis_biota' => 'Bintang Laut'],
            ['jenis_biota' => 'Kuda Laut'],
        ]);
    }
}
