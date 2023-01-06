<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Employee entity model.
 *
 * @author Imtiaz Rahi
 * @since 2023-01-07
 */
class Employee extends Model
{
    protected $table = 'employees';
    public $timestamps = true;
    public string $id = 'id';
}
