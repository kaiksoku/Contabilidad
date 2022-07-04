<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clave')->insert([
            'cla_empresa'=>'1',
            'cla_UsuarioFirma'=>'MEGA_DEMO',
            'cla_LlaveFirma'=>'e209d46a2f8f1e5b64e37b2e66da7773',
            'cla_UsuarioApi'=>'MEGA_DEMO',
            'cla_LlaveApi'=>'A51CF6BC73531ADF7D9ED8BECFA6E37B',
            'created_at'=> Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
