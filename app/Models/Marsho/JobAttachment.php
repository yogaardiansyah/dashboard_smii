<?php

namespace App\Models\Marsho;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql_job';
    protected $table = 'marsho_job_attachments';
    const UPDATED_AT = null;
    protected $fillable = ['job_id', 'job_route_id', 'file_path', 'file_name', 'uploaded_by', 'uploaded_at'];

    /**
     * Define the relationship to the user who uploaded the attachment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedByUser()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
