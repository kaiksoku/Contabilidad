<?php

namespace App\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class DetalleTurnos extends Model
{
    protected $table = 'detalleturnos';
    protected $fillable = ['dett_reporte', 'dett_salario','dett_turnos','dett_extras','dett_ordinales','dett_descripcion'];
    protected $guarded = 'dett_id';
    protected $primaryKey = 'dett_id';
    protected static $logName = 'detalleturnos';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;


    public function ReporteTurnos()
    {
        return $this->belongsTo('App\Models\Planilla\ReporteTurnos', 'dett_reporte','rept_id');
    }
    public function Salario()
    {
        return $this->belongsTo('App\Models\Planilla\Salarios', 'dett_salario','sal_id');
    }
}
