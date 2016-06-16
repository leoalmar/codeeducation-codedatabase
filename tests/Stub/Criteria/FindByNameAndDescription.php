<?php

namespace Leoalmar\CodeDatabase\Criteria;

use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

class FindByNameAndDescription implements CriteriaInterface
{

    private $name;

    private $description;

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

   
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('name',$this->name)
                    ->where('description', $this->description);
    }
}