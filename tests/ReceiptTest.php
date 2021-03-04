<?php


namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
	/**
	 * @var Receipt
	 */
	private Receipt $Receipt;

	public function setUp(): void
	{
		$this->Receipt = new Receipt();
	}

	public function tearDown(): void
	{
		unset($this->Receipt);
	}

	/**
	 * @dataProvider provideTotal
	 *
	 * @param $items
	 * @param $expected
	 */
	public function testTotal(array $items, int $expected): void
	{
		$coupon = null;
		$output = $this->Receipt->total($items, $coupon);
		$this->assertEquals(
			$expected,
			$output,
			"When summing total should equal {$expected}"
		);
	}

	public function provideTotal(): array
	{
		return [
			['Expect total to be 16' => [1, 2, 5, 8], 16],
			['Expect total to be 14' => [-1, 2, 5, 8], 14],
			['Expect total to be 16' => [1, 2, 8], 11],
		];
	}

	public function testTotalAndCoupon(): void
	{
		$input = [0, 2, 5, 8];
		$coupon = 0.20;
		$output = $this->Receipt->total($input, $coupon);
		$this->assertEquals(
			12,
			$output,
			'When summing total should equal 12'
		);
	}

	public function testTotalException(): void
	{
		$input = [0, 2, 5, 8];
		$coupon = 1.20;
		$this->expectException('BadMethodCallException');
		$this->Receipt->total($input, $coupon);
	}

	public function testPostTaxTotal(): void
	{
		$items = [0, 2, 5, 8];
		$tax = 0.20;
		$coupon = null;

		$Receipt = $this
			->getMockBuilder('TDD\Receipt')
			->setMethods(['tax', 'total'])
			->getMock();
		$Receipt
			->expects($this->once())
			->method('total')
			->with($items, $coupon)
			->will($this->returnValue(10.00));
		$Receipt
			->expects($this->once())
			->method('tax')
			->with(10.00, $tax)
			->will($this->returnValue(1.00));
		$result = $Receipt->postTaxTotal($items, $tax, $coupon);

		$this->assertEquals(11.00, $result);
	}

	public function testTax(): void
	{
		$inputAmount = 10.00;
		$taxInput = 0.1;
		$output = $this->Receipt->tax($inputAmount, $taxInput);
		$this->assertEquals(
			1.00,
			$output,
			'The tax calculation should equal 1.00'
		);
	}

	/**
	 * @param $input
	 * @param $expected
	 * @param $msg
	 *
	 * @dataProvider provideCurrencyAmount
	 */
	public function testCurrencyAmount($input, $expected, $msg): void
	{
		$this->assertSame(
			$expected,
			$this->Receipt->currencyAmount($input),
			$msg
		);
	}

	public function provideCurrencyAmount()
	{
		return [
			[1, 1.00, '1 should be transformed into 1.00'],
			[1.1, 1.10, '1.1 should be transformed into 1.10'],
			[1.11, 1.11, '1.11 should stay as 1.11'],
			[1.111, 1.11, '1.111 should be transformed into 1.11'],
		];
	}
}
