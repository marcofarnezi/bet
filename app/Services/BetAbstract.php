<?php
namespace App\Services;

use App\Contracts\BetInterface;
use App\Contracts\RuleInterface;

/**
 * Class BetAbstract
 * @package App\Services
 */
abstract class BetAbstract implements BetInterface
{
    protected $betAmount;
    protected $boardConfig = [];
    protected $rulesLines = [];
    protected $prizeRules = [];
    protected $board = [];
    protected $rules;
    protected $winningGame = [];
    protected $winTotal = 0;

    /**
     * BetAbstract constructor.
     * @param RuleInterface $rule
     * @param int $bet_amount
     */
    public function __construct(RuleInterface $rule, $bet_amount = 100)
    {
        $this->start($bet_amount);
        $this->loadRules($rule);
        $this->loadConfig();
        $this->loadBoard();
    }

    /**
     * @param int $bet_amount
     * @return int
     */
    public function start($bet_amount): int
    {
        $this->betAmount = $bet_amount;
        return $bet_amount;
    }

    /**
     * @param array $rule_lines
     * @return array
     */
    public function addRuleToCheck(array $rule_lines): array
    {
        array_push($this->rulesLines, $rule_lines);
        return $this->rulesLines;
    }

    /**
     * @param int $sequence
     * @param int $premium
     * @return array
     */
    public function addPrizeRules(int $sequence, int $premium) : array
    {
        $this->prizeRules[$sequence] = $premium;
        return $this->prizeRules;
    }

    /**
     * @return array
     */
    public function loadBoard() : array
    {
        $this->generateGame();
        return $this->board;
    }

    /**
     * @return mixed
     */
    public function checkResults()
    {
        $this->checkBetResults();
        return $this->returnResults();
    }

    /**
     * @param RuleInterface $rule
     * @return array
     */
    public function loadRules(RuleInterface $rule): array
    {
        $this->rules = $rule->getRules();
        return $this->rules;
    }

    abstract public function generateGame();
    abstract public function checkBetResults();
    abstract public function returnResults();
    abstract public function loadConfig();
}
