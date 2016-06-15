<?php

namespace Leoalmar\CodeDatabase\Tests;

use Leoalmar\CodeDatabase\Models\Category;
use Leoalmar\CodeDatabase\Repository\CategoryRepository;
use Mockery as M;

class CategoryRepositoryTest extends AbstractTestCase
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

    public function test_can_model()
    {
        $this->assertEquals(Category::class, $this->repository->model());
    }

    public function test_can_makemodel()
    {
        $result = $this->repository->makeModel();
        $this->assertInstanceOf(Category::class, $result);

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_can_make_model_in_contructor()
    {
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_can_list_all_categories()
    {
        $result = $this->repository->all();
        $this->assertCount(3,$result);
        $this->assertNotNull($result[0]->description);
        
        $result = $this->repository->all(['name']);
        $this->assertNull($result[0]->description);
    }
    
    public function test_can_create_categories()
    {
        $result = $this->repository->create([
            'name' => 'Category 4', 
            'description' => 'Description 4'
        ]);
        
        $this->assertInstanceOf(Category::class,$result);
        $this->assertEquals('Category 4',$result->name);
        $this->assertEquals('Description 4',$result->description);

        $result = $this->repository->all();
        $this->assertCount(4,$result);

        $result = Category::find(4);
        $this->assertEquals('Category 4',$result->name);
        $this->assertEquals('Description 4',$result->description);
    }

    public function test_can_update_categories()
    {
        $result = $this->repository->update([
            'name' => 'Category updated',
            'description' => 'Description updated'
        ], 1);

        $this->assertInstanceOf(Category::class,$result);
        $this->assertEquals('Category updated',$result->name);
        $this->assertEquals('Description updated',$result->description);

        $result = Category::find(1);
        $this->assertEquals('Category updated',$result->name);
        $this->assertEquals('Description updated',$result->description);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_can_update_categories_fail()
    {
        $this->repository->update([
            'name' => 'Category updated',
            'description' => 'Description updated'
        ], 10);
    }

    public function test_can_delete_categories()
    {
        $result = $this->repository->delete(1);
        $categories = Category::all();
        $this->assertCount(2,$categories);
        $this->assertEquals(true,$result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_can_delete_categories_fail()
    {
        $this->repository->delete(10);
    }

    public function test_can_find_category()
    {
        $result = $this->repository->find(1);
        $this->assertInstanceOf(Category::class,$result);
    }

    public function test_can_find_category_with_columns()
    {
        $result = $this->repository->find(1,['name']);
        $this->assertInstanceOf(Category::class,$result);
        $this->assertNull($result->description);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_can_find_category_fail()
    {
        $this->repository->find(10);
    }

    public function test_can_find_categories()
    {
        $result = $this->repository->findBy('name', 'Category 1');
        $this->assertCount(1,$result);
        $this->assertInstanceOf(Category::class,$result[0]);
        $this->assertEquals('Category 1',$result[0]->name);

        $result = $this->repository->findBy('name', 'Category 10');
        $this->assertCount(0,$result);
    }

    public function test_can_find_categories_with_columns()
    {
        $result = $this->repository->findBy('name', 'Category 1',['name']);
        $this->assertCount(1,$result);
        $this->assertInstanceOf(Category::class,$result[0]);
        $this->assertNull($result[0]->description);
    }


    private function createCategory(){
        Category::create(['name' => 'Category 1','description' => 'Descrição 1']);
        Category::create(['name' => 'Category 2','description' => 'Descrição 2']);
        Category::create(['name' => 'Category 3','description' => 'Descrição 3']);
    }
}