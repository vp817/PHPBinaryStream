<?php

/**
 *
 *                         .ooooo.     .o   ooooooooo
 *                        d88'   `8. o888  d"""""""8'
 * oooo    ooo oo.ooooo.  Y88..  .8'  888        .8'
 *  `88.  .8'   888' `88b  `88888b.   888       .8'
 *   `88..8'    888   888 .8'  ``88b  888      .8'
 *    `888'     888   888 `8.   .88P  888     .8'
 *     `8'      888bod8P'  `boood8'  o888o   .8'
 *              888
 *             o888o
 *
 * @author vp817
 *
 * Copyright (C) 2023  vp817
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

declare(strict_types=1);

namespace vp817;

use ErrorException;

use function is_bool;
use function unpack;
use function error_get_last;

class Buffer
{

	/**
	 * @param string $bytes
	 */
	public function __construct(public string $bytes = "")
	{
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function toSignedShort(int $value): int
	{
		return $value < (1 << 15) ? $value : $value - (1 << 16);
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function toSignedTriad(int $value): int
	{
		return $value < (1 << 23) ? $value : $value - (1 << 24);
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function toSignedInt(int $value): int
	{
		return $value < (1 << 31) ? $value : $value - (1 << 32);
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function toSignedLongLong(int $value): int
	{
		return $value < (1 << 63) ? $value : $value - (1 << 64);
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function limitTriad(int $value): int
	{
		return $value & 0x00ffffff;
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function limitUnsignedInt(int $value): int
	{
		return $value < 0 ? $value | $value : $value & 0xffffffff;
	}

	/**
	 * @return int
	 */
	public function getLength(): int
	{
		return strlen($this->bytes);
	}

	/**
	 * @param Buffer $buffer
	 * @return void
	 */
	public function write(Buffer $buffer): void
	{
		$this->bytes .= $buffer->bytes;
	}
	
	/**
	 * @param int $offset
	 * @param int $size
	 * @return Buffer
	 */
	public function read(int $offset, int $size): Buffer
	{
		return new Buffer(substr($this->bytes, $offset, $size));
	}

	/**
	 * @param string $format
	 * @param int $offset
	 * @return array
	 */
	public function unpack(string $format, int $offset = 0): array
	{
		$value = @unpack($format, $this->bytes, $offset);
		if (is_bool($value))
		{
			throw new ErrorException(join(" ", error_get_last()));
		}
		return $value;
	}
}