<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected array $dates = ['deleted_at'];

    protected $fillable = ['name', 'nip', 'address', 'city', 'post_code'];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(fn (Company $company) => $company->employees()->delete());
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
