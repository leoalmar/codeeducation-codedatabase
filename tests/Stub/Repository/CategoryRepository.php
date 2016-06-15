<?php

namespace Leoalmar\CodeDatabase\Repository;

use Leoalmar\CodeDatabase\AbstractRepository;
use Leoalmar\CodeDatabase\Models\Category;

class CategoryRepository extends AbstractRepository
{
    public function model()
    {
        return Category::class;
    }
}