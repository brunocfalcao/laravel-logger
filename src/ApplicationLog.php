<?php

namespace Brunocfalcao\Logger;

use Brunocfalcao\Cerebrus\Cerebrus;
use Eduka\Cube\Models\ApplicationLog as ApplicationLogModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApplicationLog
{
    protected $group;

    protected $relatedTo;

    protected $properties = [];

    protected $description;

    public function __construct()
    {
        //
    }

    public static function make(...$args): self
    {
        return new self(...$args);
    }

    /**
     * Extra properties we want to record into the log.
     *
     * @return self
     */
    public function properties(array $properties)
    {
        return tap($this, function () use ($properties) {
            $this->properties = array_merge($this->properties, $properties);
        });
    }

    /**
     * A custom related model that will be related with the log entry.
     *
     * @return self
     */
    public function relatedTo(Model $model)
    {
        return tap($this, function () use ($model) {
            $this->relatedTo = $model;
        });
    }

    public function group(string $name)
    {
        return tap($this, function () use ($name) {
            $this->group = $name;
        });
    }

    public function log(string $description = null)
    {
        // Aux variable to be used in the throw() if necessary.
        $this->description = $description;

        $log = new ApplicationLogModel();

        // Morphable relationships: causable and relatable.
        if (Auth::user()) {
            $log->causable()->associate(Auth::user());
        }/* else {
            $log->causable()->associate(Visit::get());
        }*/

        if ($this->relatedTo) {
            $log->relatable()->associate($this->relatedTo);
        }

        $log->group = $this->group;
        $log->session_id = (new Cerebrus())->getId();
        $log->description = $description;
        $log->properties = $this->properties;

        $log->push();

        return $this;
    }

    public function throw(string $message = null)
    {
        /**
         * If we don't pass a description, it tries to fallback to
         * the customized description.
         */
        if (is_null($message)) {
            $message = $this->description;
        }

        throw new \Exception($message);
    }
}
