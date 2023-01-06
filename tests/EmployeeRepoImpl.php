<?php

use EloquentRepo\EloquentBaseRepository;
use EmployeeRepo;
use Employee;

/**
 * Repository implementation of EmployeeRepo for Employee entity model.
 *
 * @author Imtiaz Rahi
 * @since 2023-01-07
 */
class EmployeeRepoImpl extends EloquentBaseRepository implements EmployeeRepo
{

    protected $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

}
