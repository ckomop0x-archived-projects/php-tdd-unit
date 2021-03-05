<?php

namespace TDD;

class Formatter
{
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
