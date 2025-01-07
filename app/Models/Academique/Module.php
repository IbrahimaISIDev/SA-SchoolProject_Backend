<?php

namespace App\Models\Academique;
use App\Models\Planification\Cours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}
