<?php

namespace Loyalty\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoints extends Model
{
    /**
     * @var string
     */
    protected $table = 'loyalty_points';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pointable()
    {
        return $this->morphTo();
    }

    /**
     * @param Model $pointable
     *
     * @return static
     */
    public function getCurrentPoints(Model $pointable)
    {
        $currentPoint = LoyaltyPoints::
        where('pointable_id', $pointable->id)
            ->where('pointable_type', $pointable->getMorphClass())
            ->orderBy('created_at', 'desc')
            ->pluck('current')->first();

        if (!$currentPoint) {
            $currentPoint = 0.0;
        }

        return $currentPoint;
    }

    /**
     * @param Model $pointable
     * @param $amount
     * @param $message
     * @param $data
     *
     * @return static
     */
    public function earnTransaction(Model $pointable, $amount, $message, $data = null)
    {
        $transaction = new static();
        $transaction->amount = $amount;

        $transaction->current = $this->getCurrentPoints($pointable) + $amount;

        $transaction->message = $message;
        if ($data) {
            $transaction->fill($data);
        }
        // $transaction->save();
        $pointable->transactions()->save($transaction);

        return $transaction;
    }

    /**
     * @param Model $pointable
     * @param $amount
     * @param $message
     * @param null $data
     * @return $this
     */
    public function burnTransaction(Model $pointable, $amount, $message, $data = null)
    {
        $transaction = new static();
        $transaction->amount = -$amount;

        $transaction->current = $this->getCurrentPoints($pointable) - $amount;

        $transaction->message = $message;
        if ($data) {
            $transaction->fill($data);
        }
       
        if ($this->getCurrentPoints($pointable) <= 0) {
            return $transaction;
        }
        $pointable->transactions()->save($transaction);

        return $transaction;
    }
}
