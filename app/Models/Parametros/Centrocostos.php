<?php

namespace App\Models\Parametros;

use Illuminate\Database\Eloquent\Model;

class Centrocostos extends Model
{
    protected $table = 'centrocosto';
    protected $fillable = ['cco_codigo','cco_descripcion','cco_regimen'];
    protected $guarded = 'cco_id';
    protected $primaryKey = 'cco_id';
    protected static $logName = 'centrocosto';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
}
