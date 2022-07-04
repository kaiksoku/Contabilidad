<?php

namespace App\Models\cyb;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DetalleAnticipoImportacion extends Model
{
    use LogsActivity;

    protected $table = 'detalleanticipoimport';
    protected $fillable = ['dant_linea', 'dant_anticipo', 'dant_tipo', 'dant_documento', 'dant_monto', 'dant_estado'];
    protected $guarded = 'dant_linea';
    protected $primaryKey = 'dant_linea';
    protected static $logName = 'detalleanticipoimport';
    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

    public function DetalleAnticipo()
    {
        return $this->belongsTo('App\Models\cyb\Anticipos', 'dant_anticipo', 'ant_id');
    }

    public function Importacion()
    {
        return $this->belongsTo('App\Models\cxp\Importacion', 'dant_tipo', 'poim_id');
    }

    public function Movimiento()
    {
        return $this->belongsTo('App\Models\Admin\MovimientoBancario', 'dant_tipo', 'movb_id');
    }

    public static function totalDetallesAnticipo($id)
    {
        return DetalleAnticipo::where('dant_anticipo', $id)->sum('dant_monto');
    }

}
