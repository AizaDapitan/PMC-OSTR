<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class RequestedItem extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'stock_code', 'description', 'uom', 'available_qty', 'requested_qty',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'requested_by', 'transaction_no'
    ];
    protected $auditInclude = [
        'stock_code', 'description', 'uom', 'available_qty', 'requested_qty',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'requested_by', 'transaction_no'
    ];
    protected $appends = ['issued_qty'];

    public function getIssuedQtyAttribute()
    {
        $issuances = IssuedItem::where('item_id', $this->id)->get();
        $issued_qty = 0;
        foreach ($issuances as $issuance) {
            $issued_qty += $issuance->issuance_qty;
        }
        return $issued_qty;
    }
}
