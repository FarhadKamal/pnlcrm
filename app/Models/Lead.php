<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
        'customer_id',
        'lead_source',
        'product_requirement',
        'lead_person',
        'lead_email',
        'lead_phone',
        'current_stage',
        'current_subStage',
        'is_return',
        'is_won',
        'is_lost',
        'lost_reason',
        'lost_description',
        'need_credit_approval',
        'need_discount_approval',
        'need_top_approval',
        'payment_type',
        'accounts_clearance',
        'is_outstanding',
        'sap_invoice',
        'invoice_date'
    ];

    public function clientInfo(){
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function source()
    {
        return $this->hasOne(LeadSource::class, 'id', 'lead_source');
    }
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function selectedPump()
    {
        return $this->hasMany(PumpChoice::class, 'lead_id', 'id');
    }
}
