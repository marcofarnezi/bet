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
    protected $bet_amount;
    protected $board_config = [];
    protected $rules_lines = [];
    protected $prize_rules = [];
    protected $board = [];
    protected $rules;
    protected $winning_game = [];
    protected $win_total = 0;

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
        $this->bet_amount = $bet_amount;
        return $bet_amount;
    }

    /**
     * @param array $rule_lines
     * @return array
     */
    public function addRuleToCheck(array $rule_lines): array
    {
        array_push($this->rules_lines, $rule_lines);
        return $this->rules_lines;
    }

    /**
     * @param int $sequence
     * @param int $premium
     * @return array
     */
    public function addPrizeRules(int $sequence, int $premium) : array
    {
        $this->prize_rules[$sequence] = $premium;
        return $this->prize_rules;
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
