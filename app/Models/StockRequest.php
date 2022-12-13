<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;
class StockRequest extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'date_filed', 'time_filed', 'date_needed', 'dept', 'cost_code', 'remarks', 'requested_by',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','status','WFS_connection','isSaved','active','transaction_no'
    ];
    protected $auditInclude = [
        'date_filed', 'time_filed', 'date_needed', 'dept', 'cost_code', 'remarks', 'requested_by',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','status','WFS_connection','isSaved','active','transaction_no'
    ];
}
