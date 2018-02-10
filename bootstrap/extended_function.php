<?php

class ex
{

	/**
	 * ~ Function ~
	 * How to use in blade:
	 *
	 * {{ (new ex())->example2() }} // It will print 123
	 */
	public function example1()
	{
		return 123;
	}

	/**
	 * ~ Static function ~
	 * How to use in blade:
	 *
	 * {{ ex::example() }} // It will print 123
	 */
	public static function example2()
	{
		return 123;
	}
}

/**
 * ~ Global function ~
 * How to use in blade:
 *
 * {{ example() }} // It will print 123
 */
function example()
{
	return 123;
}
