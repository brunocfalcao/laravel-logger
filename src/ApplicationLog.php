<?php

namespace Brunocfalcao\Logger;

use Brunocfalcao\Cerebrus\Cerebrus;
use Eduka\Cube\Models\ApplicationLog as ApplicationLogModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApplicationLog
{
    public static function __callStatic($method, $args)
    {
        return ApplicationLogService::new()->{$method}(...$args);
    }
}

class ApplicationLogService
{
    protected $group;

    protected $relatedTo;

    protected $properties = [];

    protected $description;

    public function __construct()
    {
        //
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    /**
     * Extra properties we want to record into the log.
     *
     * @param  array  $properties
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
     * @param  Model  $model
     * @return self
     */
    public function relatedTo(Model $model)
    {
        $this->relatedTo = $model;

        return $this;
    }

    public function group(string $name)
    {
        return tap($this, function () use ($name) {
            $this->group = $name;
        });
    }

    public function log(string $description = null)
    {
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
        if (is_null($message)) {
            $message = $this->description;
        }

        throw new \Exception($message);
    }
}
