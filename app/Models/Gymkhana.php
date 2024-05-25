<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Gymkhana extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $fillable = [
        'name',
        'password',
        'description',
        'amount_of_codes',
        'starting_date',
        'starting_point',
        'gymkhana_pic',
        'state',
        'availability',
    ];

    protected $casts = [
        'starting_point' => Point::class,
    ];

    public function participations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Participation::class, 'id_gymkhana');
    }

    public function codes_to_validate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Code_to_validate::class, 'id_gymkhana');
    }

    public function scopeWithinDistanceOf(Builder $query, $latitude, $longitude, $distance)
    {
        return $query->whereRaw("ST_Distance_Sphere(starting_point, ST_GeomFromText('POINT($latitude $longitude)', 4326)) <= $distance");
    }
}
