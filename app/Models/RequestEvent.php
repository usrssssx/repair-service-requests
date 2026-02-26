<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'repair_request_id',
        'actor_id',
        'action',
        'from_status',
        'to_status',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(RepairRequest::class, 'repair_request_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
