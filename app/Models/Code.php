<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Code extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $fillable = [
        'code_pic',
        'location',
        'availability'
    ];

    protected $casts = [
        'location' => Point::class,
    ];

    public function relations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Relation::class, 'id_code');
    }

    public function codes_to_validate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Code_to_validate::class, 'id_code');
    }

    public function scopeWithinDistanceOf(Builder $query, $latitude, $longitude, $distance)
    {
        return $query->whereRaw("ST_Distance_Sphere(location, ST_GeomFromText('POINT($latitude $longitude)')) <= $distance");
    }
}
