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
        $stub = $this->getMockForAbstractClass(RuleAbstract::class);
        $this->assertInstanceOf(RuleInterface::class, $stub);
    }

    public function test_return_get_rules()
    {
        $stub = $this->getMockForAbstractClass(RuleAbstract::class);

        $stub->expects($this->any())
            ->method('getRulesPath')
            ->will($this->returnValue('./app/Rules/Json/BetGame.json'));

        $result = $stub->getRules();
        $this->assertCount(4, $result);
        $this->assertTrue(array_key_exists('board_config', $result));
    }
}
