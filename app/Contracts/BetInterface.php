<?php
namespace App\Contracts;

interface BetInterface
{
    public function start(int $points);
    public function loadRules();
    public function checkResults();
}
