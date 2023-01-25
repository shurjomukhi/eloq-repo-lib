<?php

namespace EloquentRepo;

use EloquentRepo\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Imtiaz Rahi
 * @since 2023-01-07
 */
interface ShurjopayRepositoryInterface extends EloquentRepositoryInterface
{

    /** Get a record by slug or short code */
    public function get($slug_code, array $columns = ['*']): ?Model;

}