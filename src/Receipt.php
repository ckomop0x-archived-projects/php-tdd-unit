<?php


namespace TDD;

use \BadMethodCallException;

class Receipt
{
	public function __construct($formatter)
	{
		$this->Formatter = $formatter;
	}

	/**
	 * @param int[]          $items
	 * @param float|int|null $coupon
	 *
	 * @return float|int
	 */
	public function subtotal(array $items, float|int|null $coupon): float|int
	{
		if ($coupon > 1.00) {
			throw new BadMethodCallException('Coupon must be less than or equal to 1.00');
		}
		$sum = array_sum($items);
		if (!is_null($coupon)) {
			return $sum - $sum * $coupon;
		}

		return $sum;
	}

	/**
	 * @param float|int $amount
	 *
	 * @return float|int
	 */
	public function tax(float|int $amount): float|int
	{
		return $this->Formatter->currencyAmount($amount * $this->tax);
	}

	/**
	 * @param array          $items
	 * @param float|int|null $coupon
	 *
	 * @return float|int
	 */
	public function postTaxTotal(array $items, float|int|null $coupon): float|int
	{
		$subtotal = $this->subtotal($items, $coupon);
		return $subtotal + $this->tax($subtotal);
	}
}
