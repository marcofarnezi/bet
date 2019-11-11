<?php
namespace Unit;

use App\Rules\BetRule;
use App\Services\BetAbstract;
use App\Services\BetGameService;

/**
 * Class BetGameServiceTest
 * @package Unit
 */
class BetGameServiceTest extends \TestCase
{
    /**
     * @var BetRule
     */
    private $ruleMock;

    /**
     * @var BetGameService
     */
    private $betGame;

    /**
     * BetGameServiceTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $responseRule = $this->getResponseRule();
        $this->betGame = new BetGameService($this->getMockRule($responseRule));
        $this->betGame->loadConfigs();

    }

    public function test_if_implements_betabstract()
    {
        $this->assertInstanceOf(BetAbstract::class, $this->betGame);
    }

    public function test_start_value()
    {
        $return = $this->betGame->start(100);
        $this->assertEquals(100, $return);
    }

    public function test_add_rule_to_check_return()
    {
        $rule = [1, 3, 2, 5, 4];
        $return = $this->betGame->addRuleToCheck($rule);
        $this->assertCount(6, $return);
        $this->assertEquals($rule, last($return));
    }

    public function test_add_prize_rules_return()
    {
        $sequence = 1;
        $premium = 0;
        $return = $this->betGame->addPrizeRules($sequence, $premium);
        $this->assertCount(4, $return);
        $this->assertEquals($premium, $return[$sequence]);
    }

    public function test_load_board_return()
    {
        $return = $this->betGame->loadBoard();
        $this->assertCount(15, $return);
        $rules = $this->ruleMock->getRules();
        foreach ($return as $elements) {
            $this->assertTrue(in_array($elements, $rules['values_board']));
        }
    }

    public function test_load_rules_return()
    {
        $return = $this->betGame->loadRules($this->ruleMock);
        $rules = $this->getResponseRule();

        $this->assertEquals($rules, $return);
    }

    public function test_generate_game_result()
    {
        $return = $this->betGame->generateGame();
        $this->assertCount(15, $return);
        $rules = $this->ruleMock->getRules();
        foreach ($return as $elements) {
            $this->assertTrue(in_array($elements, $rules['values_board']));
        }
    }

    public function test_generate_values_board()
    {
        $betGameReflection = new \ReflectionClass(BetGameService::class);
        $betGameReflection->newInstanceArgs([$this->ruleMock]);
        $method = $betGameReflection->getMethod('generateValuesBoard');
        $method->setAccessible(true);
        $rules = $this->ruleMock->getRules();

        for ($i = 0; $i < 100; $i++) {
            $return = $method->invoke($this->betGame);
            $this->assertTrue(in_array($return, $rules['values_board']));
        }
    }

    public function test_check_bet_results_return()
    {
        $rule = $this->getResponseRule();
        $rule['values_board'] = ['A'];
        $betGame = new BetGameService($this->getMockRule($rule));
        $betGame->loadConfigs();
        $total = $betGame->checkBetResults();

        $this->assertEquals(5000, $total);
    }

    public function test_calculate_win_total_return()
    {
        $betGameReflection = new \ReflectionClass(BetGameService::class);
        $betGameReflection->newInstanceArgs([$this->ruleMock]);
        $method = $betGameReflection->getMethod('calculateWinTotal');
        $method->setAccessible(true);
        $winning_game = [3];
        $return = $method->invokeArgs($this->betGame, [$winning_game]);
        $this->assertEquals(20, $return);

        $winning_game = [4];
        $return = $method->invokeArgs($this->betGame, [$winning_game]);
        $this->assertEquals(220, $return);

        $winning_game = [5];
        $return = $method->invokeArgs($this->betGame, [$winning_game]);
        $this->assertEquals(1220, $return);
    }

    public function test_check_sequence_return()
    {
        $betGameReflection = new \ReflectionClass(BetGameService::class);
        $betGameReflection->newInstanceArgs([$this->ruleMock]);
        $method = $betGameReflection->getMethod('checkSequence');
        $method->setAccessible(true);

        $line = ['teste'];
        $sequence = 3;
        $return = $method->invokeArgs($this->betGame, [$line, $sequence]);
        $this->assertTrue(array_key_exists('teste', $return));

        $line = ['teste2'];
        $sequence = 2;
        $return = $method->invokeArgs($this->betGame, [$line, $sequence]);
        $this->assertFalse(array_key_exists('teste2', $return));
    }

    public function test_load_config_success()
    {
        $this->assertTrue($this->betGame->loadAllRules());
    }

    public function test_check_field_error()
    {
        $betGameReflection = new \ReflectionClass(BetGameService::class);
        $betGameReflection->newInstanceArgs([$this->ruleMock]);
        $method = $betGameReflection->getMethod('checkField');
        $method->setAccessible(true);

        $this->expectException(\UnexpectedValueException::class);
        $method->invokeArgs($this->betGame, ['teste']);

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
