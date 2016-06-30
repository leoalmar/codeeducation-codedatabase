<?php

namespace Leoalmar\CodeDatabase\Criteria;

use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

class FindByDescription implements CriteriaInterface
{
    private $description;

    public function __construct($description)
    {
        $this->description = $description;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('description', $this->description);
    }
}