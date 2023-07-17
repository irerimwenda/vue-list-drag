<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * A project belongs to an owner.
     *
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A project has many tasks.
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the path to the project.
     *
     * @return string
     */
    public function path(): string
    {
        return "/projects/{$this->id}";
    }
}
