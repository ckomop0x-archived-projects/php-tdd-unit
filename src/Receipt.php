<?php


namespace TDD;

use \BadMethodCallException;

class Receipt
{
	/**
	 * @param int[]          $items
	 * @param float|int|null $coupon
	 *
	 * @return float|int
	 */
	public function total(array $items, float|int|null $coupon): float|int
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
	 * @param float|int $tax
	 *
	 * @return float|int
	 */
	public function tax(float|int $amount, float|int $tax): float|int
	{
		return ($amount * $tax);
	}

	/**
	 * @param array          $items
	 * @param float|int      $tax
	 * @param float|int|null $coupon
	 *
	 * @return float|int
	 */
	public function postTaxTotal(array $items, float|int $tax, float|int|null $coupon): float|int
	{
		$subtotal = $this->total($items, $coupon);
		return $subtotal + $this->tax($subtotal, $tax);
	}

	/**
	 * @param int|float $input
	 *
	 * @return float
	 */
	public function currencyAmount(int|float $input): float
	{
		return round($input, 2);
	}
}
