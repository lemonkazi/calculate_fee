<?php

namespace App\Helpers\Transaction\Withdraw;


use App\Traits\CommissionTrait;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

/**
 * Private withdrawal container.
 *
 * We use this for storing our weekly withdrawals in memory.
 *
 * @see Withdraw
 */
class PrivateWithdraw
{
    use CommissionTrait;
    /**
     * Weekly withdraws hashmap.
     *`
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
     * Get weekly withdraw hashmap array.
     *
     * @return array get withdraws list
     */
    public function getWithdraws(): array
    {
        return $this->withdraws;
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
    public function isRemain(int $userId, int $weekNo): bool
    {
        if (!isset($this->withdraws[$userId][$weekNo]['count'])) {
            return false;
        }

        $isCrossedLimit = intval($this->withdraws[$userId][$weekNo]['count']) >= Config::get('global.WEEKLY_LIMIT');

        if ($isCrossedLimit) {
            // Now reset again
            $this->withdraws[$userId][$weekNo]['count'] = 0;
            $this->withdraws[$userId][$weekNo]['total'] = 0;
            return 0;
        }

        return $isCrossedLimit;
    }

    

    /**
     * Get weekly commission.
     *
     * @param Withdraw $withdraw withdraw instance
     *
     * @return float weekly commission amount
     */
    public function getCommission(Withdraw $withdraw): float
    {
        $trnItem = $withdraw->transactionItem;
        $commissionFee = $withdraw->commissionFee;

        
        $date = Carbon::parse($trnItem->date);
        $weekNo = (int) $date->format('W');
        $weekNo += (int) $date->format('o');
        $userId = $trnItem->userId;
        $alreadyWithdrawn = false;
        if(isset($this->withdraws[$userId][$weekNo]['total']) && $this->withdraws[$userId][$weekNo]['total'] > 0){
            $alreadyWithdrawn = true;
        }
        $totalWithdrawn = isset($this->withdraws[$userId][$weekNo]['total']) ? $this->withdraws[$userId][$weekNo]['total'] : 0;
        $isRemain = $this->isRemain($userId, $weekNo); // 3 times already done or not
        $amount = (float) $trnItem->amount;
        $weeklyFreeLimit = Config::get('global.WEEKLY_FREE_LIMIT');

        // Check if this operation is in the first 3 withdraw operations per week
        if (
            $alreadyWithdrawn &&
            ($isRemain >= 1 || $totalWithdrawn >= $weeklyFreeLimit)
        ) {
            return $this->commissionFee($amount, $commissionFee);
        }

        // Increment weekly withdraws
        $this->add($userId, $weekNo, $amount);

        // Fetch total withdrawal again after add the value.
        $totalWithdrawn = isset($this->withdraws[$userId][$weekNo]['total']) ? $this->withdraws[$userId][$weekNo]['total'] : 0;

        // Check if total withdrawal is less than the free limit.
        if ($totalWithdrawn <= $weeklyFreeLimit) {
            return 0;
        }

        // If total exceeded, commission is calculated only for the exceeded amount
        if ($totalWithdrawn > $weeklyFreeLimit) {
            $amount = $totalWithdrawn - $weeklyFreeLimit;
        }

        return $this->commissionFee($amount, $commissionFee);
    }
}
