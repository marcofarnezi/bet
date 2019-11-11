<?php
namespace Unit;

use App\Contracts\BetInterface;
use App\Rules\BetRule;
use App\Rules\RuleAbstract;
use App\Services\BetAbstract;

/**
 * Class BetAbstractTest
 * @package Unit
 */
class BetAbstractTest extends \TestCase
{
    private $ruleMock;
    /**
     * @var BetAbstract|\PHPUnit\Framework\MockObject\MockObject
     */
    private $stub;

    /**
     * BetAbstractTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $responseRule = $this->getResponseRule();
        $this->getMockRule($responseRule);
        $this->stub = $this->getMockForAbstractClass(
            BetAbstract::class,
            [$this->ruleMock]
        );
    }

    public function test_if_implements_ruleinterface()
    {
        $this->assertInstanceOf(BetInterface::class, $this->stub);
    }

    public function test_start_return_int()
    {
        $betValue = $this->stub->start(10);
        $this->assertEquals(10, $betValue);
    }

    public function test_add_rule_to_check_return()
    {
        $ruleLine = ['sequence' => 2, 'value' => 10];
        $ruleReurn = $this->stub->addRuleToCheck($ruleLine);
        $this->assertEquals(2, last($ruleReurn)['sequence']);
        $this->assertEquals(10, last($ruleReurn)['value']);
    }

    public function test_add_prize_rules_return()
    {
        $sequence = 3;
        $premium = 20;
        $prizeReturn = $this->stub->addPrizeRules($sequence, $premium);
        $this->assertCount(1, $prizeReturn);
        $this->assertEquals($sequence, key($prizeReturn));
        $this->assertEquals($premium, last($prizeReturn));
    }

    public function test_load_board_result()
    {
        $this->stub->expects($this->any())
            ->method('generateGame')
            ->will($this->returnValue(['A', 'B', 'C', 'D', 'E']));
        $this->stub->loadConfigs();
        $ruleReurn = $this->stub->loadBoard();
        $this->assertCount(5, $ruleReurn);
    }

    public function test_check_results_return()
    {
        $rules = $this->stub->loadRules($this->ruleMock);

        $this->assertCount(4, $rules);
        $this->assertTrue(array_key_exists('board_config', $rules));
        $this->assertTrue(array_key_exists('pay_game', $rules));
        $this->assertTrue(array_key_exists('pay_value', $rules));
        $this->assertTrue(array_key_exists('values_board', $rules));
    }

    /**
     * @param $responseRule
     * @return BetRule|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private function getMockRule($responseRule)
    {
        $this->ruleMock = \Mockery::mock(BetRule::class);
        $this->ruleMock->shouldReceive('getRules')
            ->andReturn($responseRule);
        return $this->ruleMock;
    }

    /**
     * @return array
     */
    private function getResponseRule()
    {
        return [
            "board_config" => [
                [0, 3, 6, 9, 12],
                [1, 4, 7, 10, 13],
                [2, 5, 8, 11, 14]
            ],
            "pay_game" => [
                [0, 3, 6, 9, 12],
                [1, 4, 7, 10, 13],
                [2, 5, 8, 11, 14],
                [0, 4, 8, 10, 12],
                [2, 4, 6, 10, 14]
            ],
            "pay_value" => [
                ["sequence" => 3, "value" => 20],
                ["sequence" => 4, "value" => 200],
                ["sequence" => 5, "value" => 1000]
            ],
            "values_board" => ["A", "J", "Q", "K", "Cat", "Mon", "Bir"]
        ];

    }
}
