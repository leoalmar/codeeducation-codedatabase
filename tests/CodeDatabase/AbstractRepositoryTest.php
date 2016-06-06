<?php

namespace Leoalmar\CodeDatabase\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Leoalmar\CodeDatabase\AbstractRepository;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;
use Mockery as M;

class AbstractRepositoryTest extends AbstractTestCase
{

    public function test_is_implements_repositoryinterface()
    {
        $mock = M::mock(AbstractRepository::class);
        $this->assertInstanceOf(RepositoryInterface::class, $mock);
    }

    public function test_should_return_all_without_arguments()
    {
        $mockRepository = M::mock(AbstractRepository::class);
        $mockStd = M::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';
        $mockStd->description = 'description';

        $mockRepository->shouldReceive('all')->andReturn([$mockStd,$mockStd,$mockStd]);

        $this->assertCount(3, $mockRepository->all());
    }

    public function test_should_return_all_with_arguments()
    {
        $mockRepository = M::mock(AbstractRepository::class);
        $mockStd = M::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';
        $mockStd->description = 'description';

        $mockRepository->shouldReceive('all')->andReturn([$mockStd,$mockStd,$mockStd]);

        $this->assertCount(3, $mockRepository->all(['id','name']));
        $this->assertInstanceOf(\stdClass::class,$mockRepository->all(['id','name'])[0]);
    }

    public function test_should_return_create()
    {
        $mockRepository = M::mock(AbstractRepository::class);
        
        $mockStd = M::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('create')
            ->with(['name'=>'stdClassName'])
            ->andReturn($mockStd);

        $result = $mockRepository->create(['name'=>'stdClassName']);
        $this->assertEquals(1,$result->id);
        $this->assertInstanceOf(\stdClass::class,$result);
    }
    
    public function test_should_return_update()
    {
        $mockRepository = M::mock(AbstractRepository::class);
        
        $mockStd = M::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('update')
            ->with(['name'=>'stdClassName'],1)
            ->andReturn($mockStd);

        $result = $mockRepository->update(['name'=>'stdClassName'],1);
        $this->assertEquals(1,$result->id);
        $this->assertInstanceOf(\stdClass::class,$result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_should_update_fail()
    {
        $mockRepository = M::mock(AbstractRepository::class);
        $throw = new ModelNotFoundException();
        $throw->setModel(\stdClass::class);
        
        $mockRepository
            ->shouldReceive('update')
            ->with(['name'=>'stdClassName'],0)
            ->andThrow($throw);

        $mockRepository->update(['name'=>'stdClassName'],0);
    }
}