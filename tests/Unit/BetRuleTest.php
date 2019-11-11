<?php
namespace Unit;

use App\Rules\BetRule;
use App\Rules\RuleAbstract;

/**
 * Class BetRuleTest
 * @package Unit
 */
class BetRuleTest extends \TestCase
{
    public function test_if_implements_betruleabstract()
    {
        $rule = new BetRule();
        $this->assertInstanceOf(RuleAbstract::class, $rule);
    }

    public function test_if_return_path()
    {
        $rule = new BetRule();
        $path = $rule->getRulesPath();
        $this->assertEquals(true, is_string($path));
    }
}
