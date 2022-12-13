<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Product extends Model  implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'stock_code', 'description', 'uom', 'available_qty', 'status',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','requested_by','transaction_no'
    ];
    protected $auditInclude = [
        'stock_code', 'description', 'uom', 'available_qty', 'status',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','requested_by','transaction_no'
    ];
}
