<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;
class IssuedItem extends Model implements AuditableContract
{
    use Auditable;
    protected $fillable = [
        'item_id', 'item_code', 'issuance_qty', 'balance', 'received_by', 'issued_by', 'created_at'
    ];
    protected $auditInclude = [
        'item_id', 'item_code', 'issuance_qty', 'balance', 'received_by', 'issued_by', 'created_at'
    ];
}
