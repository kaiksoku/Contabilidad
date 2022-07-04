<?php

namespace App\Models\Planilla;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Puesto extends Model
{
    use LogsActivity;
    protected $table = 'puesto';
    protected $fillable = ['pues_desc_ct', 'pues_desc_lg', 'pues_empresa','pues_terminal'];
    protected $guarded = 'pues_id';
    protected $primaryKey = 'pues_id';
    protected static $logName = 'puesto';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    public function Terminal()
    {
        return $this->belongsTo('App\Models\Parametros\Terminal', 'pues_terminal', 'ter_id');
    }

    public function Empresa()
    {
        return $this->belongsTo('App\Models\Parametros\Empresa', 'pues_empresa', 'emp_id');
    }

}
