<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('puesto')->insert([

            [ 'pues_desc_ct' => 'ADMINISTRADOR', 'pues_desc_lg' => 'ADMINISTRADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ASIGNADOR', 'pues_desc_lg' => 'ASIGNADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ASISTENTE DE ADMINSITRACION', 'pues_desc_lg' => 'ASISTENTE DE ADMINSITRACION', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ASISTENTE DE CONTADOR', 'pues_desc_lg' => 'ASISTENTE DE CONTADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ASISTENTE DE GERENCIA GENERAL', 'pues_desc_lg' => 'ASISTENTE DE GERENCIA GENERAL', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ASISTENTE DE RECURSOS HUMANOS', 'pues_desc_lg' => 'ASISTENTE DE RECURSOS HUMANOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'AUDITOR INTERNO', 'pues_desc_lg' => 'AUDITOR INTERNO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'AYUDANTE DE MECANICO', 'pues_desc_lg' => 'AYUDANTE DE MECANICO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'AYUDANTE', 'pues_desc_lg' => 'AYUDANTE', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CAPORAL', 'pues_desc_lg' => 'CAPORAL', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CHEQUE BARCO', 'pues_desc_lg' => 'CHEQUE BARCO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CHEQUE DE REVISION', 'pues_desc_lg' => 'CHEQUE DE REVISION', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CHEQUE', 'pues_desc_lg' => 'CHEQUE', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CONDICIONISTA', 'pues_desc_lg' => 'CONDICIONISTA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CONEXIÓN Y DESCONEXION', 'pues_desc_lg' => 'CONEXIÓN Y DESCONEXION', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'CONTADOR', 'pues_desc_lg' => 'CONTADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'COORDINADOR DESCARGA CEMENTO', 'pues_desc_lg' => 'COORDINADOR DESCARGA CEMENTO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'COORDINADOR', 'pues_desc_lg' => 'COORDINADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'DESPACHADOR DE COMBUSTIBLE', 'pues_desc_lg' => 'DESPACHADOR DE COMBUSTIBLE', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'DESPACHADOR', 'pues_desc_lg' => 'DESPACHADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'DIGITADOR', 'pues_desc_lg' => 'DIGITADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ELECTRICISTA', 'pues_desc_lg' => 'ELECTRICISTA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ELECTROMECANICO ', 'pues_desc_lg' => 'ELECTROMECANICO ', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENC DE LIMPIEZA SAN MARINO', 'pues_desc_lg' => 'ENC DE LIMPIEZA SAN MARINO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENC DE LIMPIEZA', 'pues_desc_lg' => 'ENC DE LIMPIEZA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENC. DE LIMPIEZA', 'pues_desc_lg' => 'ENC. DE LIMPIEZA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENCARGADA DE LIMPIEZA', 'pues_desc_lg' => 'ENCARGADA DE LIMPIEZA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENCARGADO DE LIMPIEZA Y MANTENIMIENTO', 'pues_desc_lg' => 'ENCARGADO DE LIMPIEZA Y MANTENIMIENTO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENCARGADO DESARROLLADOR DE SISTEMAS', 'pues_desc_lg' => 'ENCARGADO DESARROLLADOR DE SISTEMAS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ENDEREZADO Y PINTURA', 'pues_desc_lg' => 'ENDEREZADO Y PINTURA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'ESTIBADOR', 'pues_desc_lg' => 'ESTIBADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'FACTURADOR', 'pues_desc_lg' => 'FACTURADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'GERENTE DE OPERACIONES PQ', 'pues_desc_lg' => 'GERENTE DE OPERACIONES PQ', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'GERENTE DE OPERACIONES', 'pues_desc_lg' => 'GERENTE DE OPERACIONES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'GERENTE GENERAL', 'pues_desc_lg' => 'GERENTE GENERAL', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'GUARDIAN', 'pues_desc_lg' => 'GUARDIAN', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'INPECTOR DE TECHOS', 'pues_desc_lg' => 'INPECTOR DE TECHOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'INSPECTOR', 'pues_desc_lg' => 'INSPECTOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'JEFE DE GRUPO VIGILANCIA', 'pues_desc_lg' => 'JEFE DE GRUPO VIGILANCIA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'JEFE DE MECANICOS', 'pues_desc_lg' => 'JEFE DE MECANICOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'LAVADOR', 'pues_desc_lg' => 'LAVADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'LIMPIEZA', 'pues_desc_lg' => 'LIMPIEZA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MANTENIMIENTO DE CAMARAS', 'pues_desc_lg' => 'MANTENIMIENTO DE CAMARAS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MANTENIMIENTO', 'pues_desc_lg' => 'MANTENIMIENTO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MANTENIMINTO DE SECOS', 'pues_desc_lg' => 'MANTENIMINTO DE SECOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MECANICO GENERAL', 'pues_desc_lg' => 'MECANICO GENERAL', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MECANICO MONTACARGAS', 'pues_desc_lg' => 'MECANICO MONTACARGAS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MECANICO', 'pues_desc_lg' => 'MECANICO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MENSAJERO', 'pues_desc_lg' => 'MENSAJERO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'MONITOREO', 'pues_desc_lg' => 'MONITOREO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR BASCULA', 'pues_desc_lg' => 'OPERADOR BASCULA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR DE MONTACARGA', 'pues_desc_lg' => 'OPERADOR DE MONTACARGA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR DE PORTA', 'pues_desc_lg' => 'OPERADOR DE PORTA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR DE PORTACONTENEDORES', 'pues_desc_lg' => 'OPERADOR DE PORTACONTENEDORES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR PORTACONTENEDOR', 'pues_desc_lg' => 'OPERADOR PORTACONTENEDOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR PORTACONTENEDORES', 'pues_desc_lg' => 'OPERADOR PORTACONTENEDORES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERADOR', 'pues_desc_lg' => 'OPERADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPERDOR DE MONTACARGAS', 'pues_desc_lg' => 'OPERDOR DE MONTACARGAS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'OPIP', 'pues_desc_lg' => 'OPIP', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'PILOTO DE BUS', 'pues_desc_lg' => 'PILOTO DE BUS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'PILOTO DE PIPA', 'pues_desc_lg' => 'PILOTO DE PIPA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'PILOTO', 'pues_desc_lg' => 'PILOTO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'RIEGO EN PIPA', 'pues_desc_lg' => 'RIEGO EN PIPA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SECRETARIA RECEPCIONISTA', 'pues_desc_lg' => 'SECRETARIA RECEPCIONISTA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SECRETARIA', 'pues_desc_lg' => 'SECRETARIA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SEGURIDAD EJECUTIVA', 'pues_desc_lg' => 'SEGURIDAD EJECUTIVA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SEGURIDAD', 'pues_desc_lg' => 'SEGURIDAD', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SOLDADOR', 'pues_desc_lg' => 'SOLDADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SOLDADORES', 'pues_desc_lg' => 'SOLDADORES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE CONT. SECOS', 'pues_desc_lg' => 'SUPERVISOR DE CONT. SECOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE LAVADO', 'pues_desc_lg' => 'SUPERVISOR DE LAVADO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE MECANICOS', 'pues_desc_lg' => 'SUPERVISOR DE MECANICOS', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE PERSONAL ', 'pues_desc_lg' => 'SUPERVISOR DE PERSONAL ', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE REFFER', 'pues_desc_lg' => 'SUPERVISOR DE REFFER', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR DE TRANSPORTE', 'pues_desc_lg' => 'SUPERVISOR DE TRANSPORTE', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISOR', 'pues_desc_lg' => 'SUPERVISOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISORA DE PERSONAL DE APOYO', 'pues_desc_lg' => 'SUPERVISORA DE PERSONAL DE APOYO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'SUPERVISORA', 'pues_desc_lg' => 'SUPERVISORA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TALLER', 'pues_desc_lg' => 'TALLER', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO BOX', 'pues_desc_lg' => 'TECNICO BOX', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO DE BOX', 'pues_desc_lg' => 'TECNICO DE BOX', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO DE CCTV', 'pues_desc_lg' => 'TECNICO DE CCTV', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO DE REEFER', 'pues_desc_lg' => 'TECNICO DE REEFER', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO DE REFFER Y AIRES', 'pues_desc_lg' => 'TECNICO DE REFFER Y AIRES', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO DE REFFER', 'pues_desc_lg' => 'TECNICO DE REFFER', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'TECNICO REEFER', 'pues_desc_lg' => 'TECNICO REEFER', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'UBICADOR', 'pues_desc_lg' => 'UBICADOR', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'VIGILANTE APOYO', 'pues_desc_lg' => 'VIGILANTE APOYO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'VIGILANTE CCTV', 'pues_desc_lg' => 'VIGILANTE CCTV', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'VIGILANTE', 'pues_desc_lg' => 'VIGILANTE', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'pues_desc_ct' => 'WINCHERO', 'pues_desc_lg' => 'WINCHERO', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);


        

    }
}
