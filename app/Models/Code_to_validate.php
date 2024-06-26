<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code_to_validate extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'codes_to_validate';

    protected $fillable = [
        'id_user',
        'id_gymkhana',
        'id_code',
        'privilege'
    ];



    public function gymkhana(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gymkhana::class, 'id_gymkhana');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function Code(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Code::class, 'id_code');
    }
}

