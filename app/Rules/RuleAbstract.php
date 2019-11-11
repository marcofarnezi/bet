<?php
namespace App\Rules;

use App\Contracts\RuleInterface;

/**
 * Class RuleAbstract
 * @package App\Rules
 */
abstract class RuleAbstract implements RuleInterface
{
    private $rules = [];

    /**
     * @return array
     */
    public function getRules() : array
    {
        if (empty($this->rules)) {
            $rulePath = $this->getRulesPath();
            $rulesJson = file_get_contents($rulePath);
            $this->rules = json_decode($rulesJson, true);
        }
        return $this->rules;
    }

    abstract public function getRulesPath();
}
