<?php

class ex
{
	/**
	 * How to use in blade:
	 *
	 * {{ ex::example() }}
	 */
	public static function example()
	{
		return 123;
	}
}

/**
 * How to use in blade:
 *
 * {{ example() }}
 */
function example()
{
	return 123;
}
