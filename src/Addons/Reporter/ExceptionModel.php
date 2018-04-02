<?php

namespace Lia\Addons\Reporter;

use Illuminate\Database\Eloquent\Model;

class ExceptionModel extends Model
{
    public static $methodColor = [
        'GET'       => 'green',
        'POST'      => 'yellow',
        'PUT'       => 'blue',
        'DELETE'    => 'red',
        'PATCH'     => 'black',
        'OPTIONS'   => 'grey',
    ];

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('lia.database.connection') ?: config('database.default'));

        $this->setTable(config('lia.database.reporter_table'));
    }
}
