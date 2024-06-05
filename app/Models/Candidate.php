<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Candidate extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'birthdate',
        'bio',
        'image',
    ];

    public function parties()
    {
        return $this->belongsToMany(
            Party::class,
            'party_candidate',
            'candidate_id',
            'party_id'
        );
    }

    public function votes()
    {
        return $this->hasMany(
            Vote::class,
        );
    }
}

