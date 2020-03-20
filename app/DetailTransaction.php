<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->user();
    }
    public function getServiceTimeAttribute()
    {
        return $this->start_date->format('d-m-Y H:i:s') . ' s/d ' . $this->end_date->format('d-m-Y H:i:s');
    }
    public function getStatusLabelAttribute()
    {
        if ($this->status == 1) {
            return '<span class="label label-success">Selesai</span>';
        }
        return '<span class="label label-default">Proses</span>';
    }
    public function transaction()
    {
        return $this->belongsTo(LaundryPrice::class, 'laundry_price_id');
    }
}
