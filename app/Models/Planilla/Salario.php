<?php

namespace App\Models\Planilla;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Salario extends Model
{
    use LogsActivity;
    protected $table = 'salarios';
    protected $fillable = ['sal_empresa', 'sal_terminal', 'sal_empleado','sal_puesto','sal_salario',
    'sal_tipo','sal_inicio',];
    protected $guarded = 'sal_id';
    protected $primaryKey = 'sal_id';
    protected static $logName = 'salarios';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

}
