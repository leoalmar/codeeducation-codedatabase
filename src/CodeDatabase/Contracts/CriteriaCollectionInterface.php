<?php

namespace Leoalmar\CodeDatabase\Contracts;


interface CriteriaCollectionInterface
{
    public function addCriteria(CriteriaInterface $criteria);

    public function getCriteriaCollection();

    public function getByCriteria(CriteriaInterface $criteria);

    public function applyCriteria();
}