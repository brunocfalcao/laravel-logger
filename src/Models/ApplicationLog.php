<?php

namespace Brunocfalcao\Logger\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $table = 'application_log';

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * What's the relatable source model that created this log entry?
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function relatable()
    {
        return $this->morphTo();
    }

    /**
     * What's the causable source model that created this log entry?
     * Normally might be a user model, but it can be anything else.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function causable()
    {
        return $this->morphTo();
    }
}
