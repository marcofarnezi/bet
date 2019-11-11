<?php
namespace Unit;

use App\Contracts\BetInterface;
use App\Rules\RuleAbstract;
use App\Services\BetAbstract;

/**
 * Class BetAbstractTest
 * @package Unit
 */
class BetAbstractTest extends \TestCase
{
    public function test_if_implements_ruleinterface()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $this->assertInstanceOf(BetInterface::class, $mock);
    }

    public function test_start_return_int()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $mock->shouldReceive('start')
            ->with(10)
            ->andReturn(10);
        $betValue = $mock->start(10);
        $this->assertEquals(10, $betValue);
    }

    public function test_add_rule_to_check_return()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $ruleLine = \Mockery::mock(\ArrayObject::class);
        $mock->shouldReceive('addRuleToCheck')
            ->with([$ruleLine, $ruleLine])
            ->andReturn([$ruleLine, $ruleLine]);

        $ruleReurn = $mock->addRuleToCheck([$ruleLine, $ruleLine]);
        $this->assertCount(2, $ruleReurn);
        $this->assertInstanceOf(\ArrayObject::class, last($ruleReurn));
    }

    public function test_add_prize_rules_return()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $sequence = 3;
        $premium = 20;

        $mock->shouldReceive('addPrizeRules')
            ->with($sequence, $premium)
            ->andReturn([$sequence => $premium]);

        $prizeReturn = $mock->addPrizeRules($sequence, $premium);
        $this->assertCount(1, $prizeReturn);
        $this->assertEquals($sequence, key($prizeReturn));
        $this->assertEquals($premium, last($prizeReturn));
    }

    public function test_load_board_result()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $boardLine = \Mockery::mock(\ArrayObject::class);
        $mock->shouldReceive('loadBoard')
            ->andReturn([$boardLine, $boardLine]);

        $ruleReurn = $mock->loadBoard();
        $this->assertCount(2, $ruleReurn);
        $this->assertInstanceOf(\ArrayObject::class, last($ruleReurn));
    }

    public function test_check_results_return()
    {
        $mock = \Mockery::mock(BetAbstract::class);
        $rule = \Mockery::mock(RuleAbstract::class);
        $ruleLine = \Mockery::mock(\ArrayObject::class);
        $mock->shouldReceive('loadRules')
            ->with($rule)
            ->andReturn([$ruleLine, $ruleLine, $ruleLine, $ruleLine]);

        $rules = $mock->loadRules($rule);
        $this->assertCount(4, $rules);
        $this->assertInstanceOf(\ArrayObject::class, last($rules));
    }
}
