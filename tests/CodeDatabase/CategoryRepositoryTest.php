<?php

namespace Leoalmar\CodeDatabase\Tests;

use Leoalmar\CodeDatabase\Models\Category;
use Leoalmar\CodeDatabase\Repository\CategoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery as M;

class CategoryRepositoryTest extends AbstractTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->migrate();
    }

    public function test_can_model()
    {
        $repository = new CategoryRepository();
        $this->assertEquals(Category::class, $repository->model());
    }
}