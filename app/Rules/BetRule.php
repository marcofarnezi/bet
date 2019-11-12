<?php
namespace App\Rules;

/**
 * Class BetRule
 * @package App\Rules
 */
class BetRule extends RuleAbstract
{
    const PATH = __DIR__ . '/Json/BetGame.json';

    /**
     * @return string
     */
    public function getRulesPath() : string
    {
        return self::PATH;
    }
}
