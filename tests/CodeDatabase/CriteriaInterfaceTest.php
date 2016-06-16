<?php

namespace Leoalmar\CodeDatabase\Tests;

use Illuminate\Database\Query\Builder;
use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;
use Leoalmar\CodeDatabase\Models\Category;
use Mockery as M;

class CriteriainterfaceTest extends AbstractTestCase
{

    public function test_should_apply()
    {
        $mockQueryBuilder = M::mock(Builder::class);
        $mockRepository = M::mock(RepositoryInterface::class);
        $mockModel = M::mock(Category::class);
        $mock = M::mock(Criteriainterface::class);
        $mock->shouldReceive('apply')
            ->with($mockModel,$mockRepository)
            ->andReturn($mockQueryBuilder)
        ;
        
        $this->assertInstanceOf(Builder::class, $mock->apply($mockModel,$mockRepository));
    }

}