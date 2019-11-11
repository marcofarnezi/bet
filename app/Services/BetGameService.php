<?php
namespace App\Services;

/**
 * Class BetGameService
 * @package App\Services
 */
class BetGameService extends BetAbstract
{
    /**
     * @return array
     */
    public function generateGame(): array
    {
        $board = [];
        foreach ($this->boardConfig as $line) {
            $lineBord = [];
            foreach ($line as $position) {
                $lineBord[$position] = $this->generateValuesBoard();
            }
            $board += $lineBord;
        }
        return $board;
    }

    /**
     * @return string
     */
    private function generateValuesBoard(): string
    {
        $size = count($this->rules['values_board']);
        $pick = rand(0, ($size - 1));
        return $this->rules['values_board'][$pick];
    }

    /**
     * @return int
     */
    public function checkBetResults(): int
    {
        foreach ($this->rulesLines as $colums => $line) {
            $sequenceCount = 0;
            $key = array_key_first(array_flip($line));
            $last = $this->board[$key];
            foreach ($line as $key) {
                $current = $this->board[$key];
                if ($current == $last) {
                    $sequenceCount++;
                    $last = $current;
                    $this->checkSequence($line, $sequenceCount);
                    continue;
                }
                $last = $current;
                $sequenceCount = 1;
            }
        }

        return $this->calculateWinTotal($this->winningGame);
    }

    /**
     * @param $winning_game
     * @return int
     */
    private function calculateWinTotal($winning_game): int
    {
        foreach ($winning_game as $premiumSequence) {
            $this->winTotal += ($this->prizeRules[$premiumSequence] * 100) / $this->betAmount;
        }

        return $this->winTotal;
    }

    /**
     * @param $line
     * @param $sequence
     * @return array
     */
    private function checkSequence($line, $sequence): array
    {
        if (array_key_exists($sequence, $this->prizeRules)) {
            $this->winningGame[implode(' ', $line)] = $sequence;
        }
        return $this->winningGame;
    }

    /**
     * @return string
     */
    private function boardString(): string
    {
        return '[' . implode(', ', $this->board) . ']';
    }

    /**
     * @return array
     */
    public function returnResults()
    {
        $result = [
            'board' => $this->boardString(),
            'paylines' => $this->winningGame,
            'bet_amount' => $this->betAmount,
            'win_total' => $this->winTotal
        ];

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * @return bool
     */
    public function loadAllRules(): bool
    {
        if ($this->isValidRule()) {
            $this->loadRuleToCheck();
            $this->loadPriceRules();
            $this->loadBoardConfig();
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    private function loadRuleToCheck(): array
    {
        foreach ($this->rules['pay_game'] as $pay_game) {
            $this->addRuleToCheck($pay_game);
        }

        return $this->rulesLines;
    }

    /**
     * @return array
     */
    private function loadBoardConfig(): array
    {
        $this->boardConfig = $this->rules['board_config'];
        return $this->boardConfig;
    }

    /**
     * @return array
     */
    private function loadPriceRules(): array
    {
        foreach ($this->rules['pay_value'] as $pay_value) {
            $this->addPrizeRules($pay_value['sequence'], $pay_value['value']);
        }

        return $this->prizeRules;
    }

    /**
     * @return bool
     */
    private function isValidRule(): bool
    {
        return $this->checkField('board_config') &&
            $this->checkField('pay_game') &&
            $this->checkField('pay_value') &&
            $this->checkField('values_board');
    }

    /**
     * @param $field
     * @return bool
     */
    private function checkField($field): bool
    {
        if (! isset($this->rules[$field])) {
            throw new \UnexpectedValueException("Rule without {$field} field");
        }

        if (! is_array($this->rules['pay_game'])) {
            throw new \UnexpectedValueException("Rule {$field} isn't a array");
        }

        return true;
    }
}
