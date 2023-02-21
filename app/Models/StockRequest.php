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
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','status','WFS_connection','isSaved','active','transaction_no',
        'isReceived','received_by','received_at','origin'
    ];
    protected $auditInclude = [
        'date_filed', 'time_filed', 'date_needed', 'dept', 'cost_code', 'remarks', 'requested_by',
        'created_by', 'updated_by', 'deleted_at', 'deleted_by','status','WFS_connection','isSaved','active','transaction_no',
        'isReceived','received_by','received_at','origin'
    ];
    protected $appends = ['completed'];

    public function getCompletedAttribute()
    {
        $completed = false;
        $requested_items = RequestedItem::where('transaction_no', $this->transaction_no)->whereNull('deleted_at')->orderBy('id','desc')->get();
        $fully_issued = 0;
        foreach($requested_items as $requested_item){
            $completed_item = IssuedItem::where([['item_id', $requested_item->id], ['balance' , 0]])->get();
            $fully_issued += $completed_item->count();
        }
        if($fully_issued == $requested_items->count()){
            $completed = true;
        }
        return $completed;
    }
}
