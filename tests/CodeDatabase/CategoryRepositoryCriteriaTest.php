<?php

namespace Leoalmar\CodeDatabase\Tests;

use Illuminate\Database\Eloquent\Builder;
use Leoalmar\CodeDatabase\Contracts\CriteriaCollectionInterface;
use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Criteria\FindByName;
use Leoalmar\CodeDatabase\Criteria\FindByNameAndDescription;
use Leoalmar\CodeDatabase\Criteria\OrderDescById;
use Leoalmar\CodeDatabase\Criteria\OrderDescByName;
use Leoalmar\CodeDatabase\Criteria\FindByDescription;
use Leoalmar\CodeDatabase\Models\Category;
use Leoalmar\CodeDatabase\Repository\CategoryRepository;
use Mockery as M;

class CategoryRepositoryCriteriaTest extends AbstractTestCase
{

    /**
     * @var CategoryRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();
        $this->repository = new CategoryRepository();
        $this->createCategory();
    }

    public function test_if_instanceof_criteriainterface()
    {
        $this->assertInstanceOf(CriteriaCollectionInterface::class, $this->repository);
    }

    public function test_can_get_criteria_collection()
    {
        $retult = $this->repository->getCriteriaCollection();
        $this->assertCount(0, $retult);
    }

    public function test_can_add_criteria()
    {
        $mockCriteria = M::mock(CriteriaInterface::class);
        $retult = $this->repository->addCriteria($mockCriteria);
        $this->assertInstanceOf(CategoryRepository::class, $retult);
        $this->assertCount(1, $this->repository->getCriteriaCollection());
    }

    public function test_can_get_by_criteria()
    {
        $criteria = new FindByNameAndDescription('Category 1', 'Description 1');
        $repository = $this->repository->getByCriteria($criteria);
        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(1, $result);
        $result = $result->first();
        $this->assertEquals($result->name, 'Category 1');
        $this->assertEquals($result->description, 'Description 1');
    }

    public function test_can_apply_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository->addCriteria($criteria1)->addCriteria($criteria2);

        $repository = $this->repository->applyCriteria();

        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();

        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category B');
        $this->assertEquals($result[1]->name, 'Category B');
        $this->assertEquals($result[2]->name, 'Category A');
    }

    public function test_can_list_all_categories_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository->addCriteria($criteria1)->addCriteria($criteria2);
        
        $result = $this->repository->all();

        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category B');
        $this->assertEquals($result[1]->name, 'Category B');
        $this->assertEquals($result[2]->name, 'Category A');
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_can_find_category_with_criteria_with_exception()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category A');

        $this->repository->addCriteria($criteria1)->addCriteria($criteria2);
        
        $this->repository->find(5);
    }

    public function test_can_find_category_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category B');

        $this->repository->addCriteria($criteria1)->addCriteria($criteria2);

        $result = $this->repository->find(5);
        $this->assertEquals($result->name,"Category B");
    }

    public function test_can_findby_categories_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria2 = new FindByName('Category B');
        $criteria1 = new OrderDescById();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $result = $this->repository->findBy('description','Description');
        $this->assertCount(2,$result);
        $this->assertEquals($result[0]->id, 6);
        $this->assertEquals($result[0]->name, 'Category B');
        $this->assertEquals($result[1]->id, 5);
        $this->assertEquals($result[1]->name, 'Category B');
    }

    public function test_can_ignore_criteria()
    {
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('isIgnoreCriteria');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->repository->ignoreCriteria();
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertTrue($result);

        $this->repository->ignoreCriteria(false);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->assertInstanceOf(CategoryRepository::class,$this->repository->ignoreCriteria());

    }

    public function test_can_ignore_criteria_with_apply_criteria()
    {

        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2)
            ->ignoreCriteria()
            ->applyCriteria();

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);

        $this->assertInstanceOf(Category::class, $result);

        
        $this->repository->ignoreCriteria(false)->applyCriteria();
        
        $this->assertInstanceOf(CategoryRepository::class, $this->repository);

        $result = $this->repository->all();

        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category B');
        $this->assertEquals($result[1]->name, 'Category B');
        $this->assertEquals($result[2]->name, 'Category A');

    }



    public function test_can_clear_criterias()
    {
        $this->createCategoryDescription();

        $criteria2 = new FindByName('Category B');
        $criteria1 = new OrderDescById();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2)
            ->clearCriteria();

        $this->assertInstanceOf(CategoryRepository::class, $this->repository->clearCriteria());
        
        $result = $this->repository->findBy('description','Description');
        $this->assertCount(3,$result);


        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);

        $this->assertInstanceOf(Category::class, $result);
    }


    private function createCategoryDescription(){
        Category::create(['name' => 'Category A','description' => 'Description']);
        Category::create(['name' => 'Category B','description' => 'Description']);
        Category::create(['name' => 'Category B','description' => 'Description']);
    }

    private function createCategory(){
        Category::create(['name' => 'Category 1','description' => 'Description 1']);
        Category::create(['name' => 'Category 2','description' => 'Description 2']);
        Category::create(['name' => 'Category 3','description' => 'Description 3']);
    }
}