<?php

namespace EloquentRepo;

use Illuminate\Database\Eloquent\Model;

class ShurjopayBaseRepository extends EloquentBaseRepository implements ShurjopayRepositoryInterface
{

    public function get($slug_code, array $columns = ['*']): ?Model
    {
        return $this->model->select($columns)->where('slug', $slug_code)->first();
    }

}