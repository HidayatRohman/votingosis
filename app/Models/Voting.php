<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'kelas',
        'jurusan',
        'foto',
        'visi',
        'misi',
    ];

    public function votes()
    {
        return $this->hasMany(\App\Models\Vote::class);
    }
}