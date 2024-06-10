<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_code',
        'privilege',
        'follow',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function Code(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Code::class, 'id_code');
    }
}
