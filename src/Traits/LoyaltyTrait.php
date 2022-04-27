<?php

namespace Loyalty\Traits;

use Loyalty\Models\LoyaltyPoints;
use Illuminate\Database\Eloquent\Model;

trait LoyaltyTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function transactions($amount = null)
    {
        return $this->morphMany(LoyaltyPoints::class, 'pointable')->orderBy('created_at', 'desc')->take($amount);
    }

    /**
     *
     * @return mix
     */
    public function averagePoint($round = null)
    {
        if ($round) {
            return $this->transactions()
                ->selectRaw('ROUND(AVG(amount), ' . $round . ') as averagePointTransaction')
                ->pluck('averagePointTransaction');
        }

        return $this->transactions()
            ->selectRaw('AVG(amount) as averagePointTransaction')
            ->pluck('averagePointTransaction');
    }

    /**
     *
     * @return mix
     */
    public function countPoint()
    {
        return $this->transactions()
            ->selectRaw('count(amount) as countTransactions')
            ->pluck('countTransactions');
    }

    /**
     *
     * @return mix
     */
    public function sumPoint()
    {
        return $this->transactions()
            ->selectRaw('SUM(amount) as sumPointTransactions')
            ->pluck('sumPointTransactions');
    }

    /**
     * @param $max
     *
     * @return mix
     */
    public function pointPercent($max = 5)
    {
        $transactions = $this->transactions();
        $quantity = $transactions->count();
        $total = $transactions->selectRaw('SUM(amount) as total')->pluck('total');
        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    /**
     *
     * @return mix
     */
    public function countTransactions()
    {
        return $this->transactions()
            ->count();
    }

    /**
     *
     * @return double
     */
    public function currentPoints()
    {
        return (new LoyaltyPoints())->getCurrentPoints($this);
    }

    /**
     * @param $amount
     * @param $message
     * @param $data
     *
     * @return static
     */
    public function earnPoints($amount, $message, $data = null)
    {
        return (new LoyaltyPoints())->earnTransaction($this, $amount, $message, $data = null);
    }

    /**
     * @param $amount
     * @param $message
     * @param null $data
     * @return mixed
     */
    public function burnPoints($amount, $message, $data = null)
    {
        return (new LoyaltyPoints())->burnTransaction($this, $amount, $message, $data = null);
    }
}
