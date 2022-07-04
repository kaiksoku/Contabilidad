<?php

namespace App\Models\cxp;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class DetallePago extends Model
{
    use LogsActivity;

    protected $table = 'detallepago';
    protected $fillable = ['dp_empresa', 'dp_numero', 'dp_serie', 'dp_pago', 'dp_tipo'];
    protected $primaryKey = ['do_empresa', 'dp_numero', 'dp_serie', 'dp_pago', 'dp_tipo'];
    protected $guarded = 'dp_empresa';
    protected static $logName = 'detallepago';
    protected static $ignoreChangedAttributes = ['created_at','updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

    public function Empresa()
    {
        return $this->belongsTo('App\Models\Parametros\Empresa', 'cla_empresa', 'emp_id');
    }



}



