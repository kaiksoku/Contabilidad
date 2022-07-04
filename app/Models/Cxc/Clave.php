<?php

namespace App\Models\Cxc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Clave extends Model
{
    use LogsActivity;

    protected $table = 'clave';
    protected $fillable = ['cla_empresa', 'cla_UsuarioFirma','cla_LlaveFirma','cla_UsuarioApi','cla_LlaveApi'];
    protected $guarded = 'cla_empresa';
    protected $primaryKey = 'cla_empresa';
    protected static $logName = 'clave';
    protected static $ignoreChangedAttributes = ['created_at','updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

    public function Empresa()
    {
        return $this->belongsTo('App\Models\Parametros\Empresa', 'cla_empresa', 'emp_id');
    }



}



