<?php

namespace App\Models\Marsho;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobNote extends Model
{
    use HasFactory;

    protected $connection = 'mysql_job';
    protected $table = 'marsho_job_notes';
    const UPDATED_AT = null;
    protected $fillable = ['job_id', 'job_route_id', 'note', 'created_by', 'created_at'];

    /**
     * Define the relationship to the user who created the note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
