<?php

namespace App\Models\Planilla;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Salarios extends Model
{
    use LogsActivity;
    protected $table = 'salarios';
    protected $fillable = ['sal_empresa', 'sal_terminal', 'sal_empleado', 'sal_puesto', 'sal_salario', 'sal_igss','sal_tipo','sal_inicio','sal_fin'];
    protected $guarded = 'sal_id';
    protected $primaryKey = 'sal_id';
    protected static $logName = 'salarios';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;


    public function Terminal()
    {
        return $this->belongsTo('App\Models\Parametros\Terminal', 'sal_terminal', 'ter_id');
    }

    public function Empresa()
    {
        return $this->belongsTo('App\Models\Parametros\Empresa', 'sal_empresa', 'emp_id');
    }
    public function Puesto()
    {
        return $this->belongsTo('App\Models\Planilla\Puesto', 'sal_puesto', 'pues_id');
    }
    public function Empleado()
    {
        return $this->belongsTo('App\Models\Planilla\Empleado', 'sal_empleado','empl_id');
    }
}
