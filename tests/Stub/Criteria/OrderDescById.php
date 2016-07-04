<?php

namespace Leoalmar\CodeDatabase\Criteria;

use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

class OrderDescById implements CriteriaInterface
{
    public function __construct()
    {

    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->orderBy('id', 'desc');
    }
}