<?php

declare(strict_types=1);

namespace App\Http\Controllers\Transaction\Withdraw;

// use App\CommissionTask\Core\DateHelper;
// use App\CommissionTask\Traits\SingletonTrait;
use App\Http\Controllers\Commission\Commission;
use DateTime;

/**
 * Weekly withdrawal container.
 *
 * We use this for storing our weekly withdrawals in memory.
 *
 * @see SingletonTrait
 * @see Withdraw
 */
class WeeklyWithdraw
{
    //use SingletonTrait;

    /**
     * Free of charge amount limit of a week.
     */
    public const WEEKLY_FREE_OF_CHARGE_LIMIT = 1000;

    /**
     * Weekly withdraws hashmap.
     *
     * @example
     *
     * ```
     * $weeklyWithdraws = [
     *  userId => [
     *     weekNo => [
     *          'count' => countNo,
     *          'total' => totalAmount
     *      ]
     *  ]
     * ]
     * ```
     */
    private array $withdraws;

    /**
     * Singleton Instance.
     *
     * @var self
     */
    private static $instance;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->withdraws = [];
    }

    /**
     * Get the singleton instance.
     *
     * @return $this
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Get week no from date in a year.
     *
     * @param string $date       Date
     * @param bool   $appendYear $appendYear or not
     *                           If true, then works with previous year too
     *
     * @return int get week no
     */
    public static function getWeekNo(string $date, bool $appendYear = false): int
    {
        $date = new DateTime($date);
        $weekNo = (int) $date->format('W');

        if ($appendYear) {
            $weekNo += (int) $date->format('o');
        }

        return $weekNo;
    }

    /**
     * Get weekly withdraw hashmap array.
     *
     * @return array get withdraws list
     */
    public function getWithdraws(): array
    {
        return $this->withdraws;
    }

    /**
     * If user already withdraw any money in this week.
     *
     * @param int $userId user id
     * @param int $weekNo week no
     *
     * @return bool If already withdrawn or not
     */
    public function isAlreadyWithdrawn(int $userId, int $weekNo): bool
    {
        return isset($this->withdraws[$userId][$weekNo]['total']) && $this->withdraws[$userId][$weekNo]['total'] > 0;
    }

    /**
     * Add and increment weekly withdraws.
     *
     * @param int   $userId user id
     * @param int   $weekNo week no
     * @param float $amount amount
     *
     * @return void add amount to user withdraws
     */
    public function add(int $userId, int $weekNo, float $amount): void
    {
        if (!isset($this->withdraws[$userId])) {
            $this->withdraws[$userId] = [];
        }

        if (!isset($this->withdraws[$userId][$weekNo]['count'])) {
            $this->withdraws[$userId][$weekNo]['count'] = 1;
            $this->withdraws[$userId][$weekNo]['total'] = $amount;
        } else {
            $this->withdraws[$userId][$weekNo]['count'] = $this->withdraws[$userId][$weekNo]['count'] + 1;
            $this->withdraws[$userId][$weekNo]['total'] = $this->withdraws[$userId][$weekNo]['total'] + $amount;
        }
    }

    /**
     * Is weekly three time withdraws amount or not.
     *
     * @param int $userId user id
     * @param int $weekNo week no
     *
     * @return bool more than or equal thrice times withdrawn or not
     */
    public function isThriceTimes(int $userId, int $weekNo): bool
    {
        if (!isset($this->withdraws[$userId][$weekNo]['count'])) {
            return false;
        }

        $isCrossedLimit = intval($this->withdraws[$userId][$weekNo]['count']) >= 3;

        if ($isCrossedLimit) {
            // Now reset again
            $this->withdraws[$userId][$weekNo]['count'] = 0;
            $this->withdraws[$userId][$weekNo]['total'] = 0;
        }

        return $isCrossedLimit;
    }

    /**
     * Get total withdrawn amount.
     *
     * @param int $userId user id
     * @param int $weekNo week no
     *
     * @return float get total withdrawn amount for a user in a week
     */
    public function getTotal(int $userId, int $weekNo): float
    {
        return isset($this->withdraws[$userId][$weekNo]['total']) ? $this->withdraws[$userId][$weekNo]['total'] : 0;
    }

    /**
     * Get weekly commission.
     *
     * Find weekly commission amount by calculating withdrawal total
     * and weekly limits.
     *
     * @param Withdraw $withdraw withdraw instance
     *
     * @return float weekly commission amount
     */
    public function getCommission(Withdraw $withdraw): float
    {
        $trnItem = $withdraw->transactionItem;
        $commissionFee = $withdraw->commissionFee;

        $weekNo = self::getWeekNo($trnItem->date, true);
        $userId = $trnItem->userId;
        $alreadyWithdrawn = $this->isAlreadyWithdrawn($userId, $weekNo);
        $totalWithdrawn = $this->getTotal($userId, $weekNo);
        $thriceTimeWithdrawn = $this->isThriceTimes($userId, $weekNo);
        $amount = (float) $trnItem->amount;
        $weeklyFreeLimit = self::WEEKLY_FREE_OF_CHARGE_LIMIT;

        // Check if this operation is in the first 3 withdraw operations per week
        // and total free limit and withdrawal amount is exceeded.
        if (
            $alreadyWithdrawn &&
            ($thriceTimeWithdrawn >= $weeklyFreeLimit || $totalWithdrawn >= $weeklyFreeLimit)
        ) {
            return Commission::calculate($amount, $commissionFee);
        }

        // Increment weekly withdraws
        $this->add($userId, $weekNo, $amount);

        // Fetch total withdrawal again after add the value.
        $totalWithdrawn = $this->getTotal($userId, $weekNo);

        // Check if total withdrawal is less than the free limit.
        if ($totalWithdrawn <= $weeklyFreeLimit) {
            return 0;
        }

        // If total free of charge amount is exceeded,
        // then commission is calculated only for the exceeded amount
        if ($totalWithdrawn > $weeklyFreeLimit) {
            $amount = $totalWithdrawn - $weeklyFreeLimit;
        }

        return Commission::calculate($amount, $commissionFee);
    }
}
