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

use function error_get_last;
use function is_bool;
use function unpack;
use function strlen;

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
	public static function toUnsignedTriad(int $value): int
	{
		return $value < 0 ? 0 : $value & 0xffffff;
	}

	/**
	 * @param int $value
	 * @return int
	 */
	public static function toUnsignedInt(int $value): int
	{
		return $value < 0 ? 0 : $value & 0xffffffff;
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
	 * @throws ErrorException
	 */
	public function unpack(string $format, int $offset = 0): array
	{
		$nullByteBE = str_contains($format, "<");
		$nullByteLE = str_contains($format, ">");
		if ($nullByteBE) {
			$this->bytes = "\x00" . $this->bytes;
		} else if ($nullByteLE) {
			$this->bytes = $this->bytes . "\x00";
		}
		if ($nullByteBE || $nullByteLE) {
			$format = str_replace(["<", ">"], "", $format);
		}
		$value = @unpack($format, $this->bytes, $offset);
		if (is_bool($value)) {
			throw new ErrorException(join(" ", error_get_last()));
		}
		return $value;
	}

	/**
	 * @return string
	 */
	public function printAsHex(): string
	{
		$result = "";
		$currentLength = $this->getLength();
		for ($i = 0; $i < $currentLength; ++$i) {
			$byte = $this->bytes[$i];
			$byteHex = bin2hex($byte);
			$result .= $byteHex;
			if ($i != $currentLength - 1) {
				$result .= " ";
			}
		}
		return $result;
	}

	/**
	 * @param bool $turnNullByteToSingle
	 * @return string
	 */
	public function printAsBytes(bool $turnNullByteToSingle = false): string
	{
		$result = "";
		for ($i = 0; $i < $this->getLength(); ++$i) {
			$byte = $this->bytes[$i];
			$byteHex = bin2hex($byte);
			if ($turnNullByteToSingle && ($byteHex == 0)) {
				$byteHex = 0;
			}
			$result .= $byteHex;
		}
		return $result;
	}
}
