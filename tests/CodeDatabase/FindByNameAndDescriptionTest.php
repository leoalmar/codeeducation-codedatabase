<?php

namespace Leoalmar\CodeDatabase\Tests;

use Illuminate\Database\Eloquent\Builder;
use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Criteria\FindByNameAndDescription;
use Leoalmar\CodeDatabase\Models\Category;
use Leoalmar\CodeDatabase\Repository\CategoryRepository;
use Mockery as M;

class FindByNameAndDescriptionTest extends AbstractTestCase
{

    /**
     * @var CategoryRepository
     */
    private $repository;

    /**
     * @var CriteriaInterface
     */
    private $criteria;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();
        $this->repository = new CategoryRepository();
        $this->criteria = new FindByNameAndDescription('Category 1','Description 1');
        $this->createCategory();
    }

    public function test_if_instanceof_criteriainterface()
    {
        $this->assertInstanceOf(CriteriaInterface::class, $this->criteria);
    }

    public function test_id_apply_returns_querybuilder()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository);
        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_id_apply_returns_data()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository)->get()->first();
        $this->assertEquals('Category 1', $result->name);
        $this->assertEquals('Description 1', $result->description);
    }

    private function createCategory(){
        Category::create(['name' => 'Category 1','description' => 'Description 1']);
        Category::create(['name' => 'Category 2','description' => 'Description 2']);
        Category::create(['name' => 'Category 3','description' => 'Description 3']);
    }

}