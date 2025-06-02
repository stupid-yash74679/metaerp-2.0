<?php

namespace App\Models\Projects; // Ensure namespace matches directory

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // For created_by relationship

class ProjectType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'stages', // JSON field for stages
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stages' => 'array', // Automatically encode/decode JSON to/from array
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the project type.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Optional: Add accessors/mutators or helper methods for stages if needed later
    // For example, a method to get a stage by its ID from the JSON array
    public function getStageById(string $stageId)
    {
        if (is_array($this->stages)) {
            foreach ($this->stages as $stage) {
                if (isset($stage['id']) && $stage['id'] === $stageId) {
                    return $stage;
                }
            }
        }
        return null;
    }
}
