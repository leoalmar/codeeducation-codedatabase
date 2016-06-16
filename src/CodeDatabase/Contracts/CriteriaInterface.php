<?php

namespace Leoalmar\CodeDatabase\Contracts;


interface CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository);
}