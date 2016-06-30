<?php

namespace Leoalmar\CodeDatabase\Criteria;

use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

class OrderDescByName implements CriteriaInterface
{
    public function __construct()
    {

    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->orderBy('name', 'desc');
    }
}