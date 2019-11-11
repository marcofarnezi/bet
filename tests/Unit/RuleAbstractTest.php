<?php
namespace Unit;

use App\Contracts\RuleInterface;
use App\Rules\RuleAbstract;

/**
 * Class RuleAbstractTest
 * @package Unit
 */
class RuleAbstractTest extends \TestCase
{

    public function test_if_implements_ruleinterface()
    {
        $mock = \Mockery::mock(RuleAbstract::class);
        $this->assertInstanceOf(RuleInterface::class, $mock);
    }

    public function test_return_get_rules()
    {
        $ruleMock = \Mockery::mock(RuleAbstract::class);
        $arrayMock = \Mockery::mock(\ArrayObject::class);

        $ruleMock->shouldReceive('getRules')
            ->andReturn([$arrayMock, $arrayMock, $arrayMock, $arrayMock]);

        $result = $ruleMock->getRules();
        $this->assertCount(4, $result);
        $this->assertInstanceOf(\ArrayObject::class,  last($result));
    }
}
