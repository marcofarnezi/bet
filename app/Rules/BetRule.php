<?php
namespace App\Rules;

use App\Contracts\RuleInterface;

/**
 * Class BetRule
 * @package App\Rules
 */
class BetRule extends RuleAbstract
{
    const PATH = __DIR__ . '/BetGame.json';

    /**
     * @return string
     */
    public function getRulesPath() : string
    {
        return self::PATH;
    }
}
