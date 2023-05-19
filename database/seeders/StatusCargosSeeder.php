<?php

namespace Database\Seeders;

use App\Models\StatusCharge;
use Illuminate\Database\Seeder;

class StatusCargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusCharge::create([
            'name' => 'Pendiente'
        ]);
        StatusCharge::create([
            'name' => 'Pagado'
        ]);
        StatusCharge::create([
            'name' => 'Pagado parcialmente'
        ]);
    }
}
