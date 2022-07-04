<?php

namespace App\Models\Planilla;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DescuentoEventual extends Model
{
    use LogsActivity;
    protected $table = 'descuentoeventual';
    protected $fillable = ['dee_salario','dee_monto', 'dee_saldo', 'dee_saldo_original', 'dee_fecha','dee_observaciones'];
    protected $guarded = 'dee_id';
    protected $primaryKey = 'dee_id';
    protected static $logName = 'descuentoeventual';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

    public function Salario()
    {
        return $this->belongsTo('App\Models\Planilla\Salarios', 'dee_salario','sal_id');
    }

}
