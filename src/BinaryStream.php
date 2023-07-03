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

use RuntimeException;

use function is_null;
use function pack;
use function substr;

class BinaryStream
{

	/** @var Buffer $buffer*/
	public Buffer $buffer;

	/**
	 * @param Buffer|null $buffer
	 * @param int $offset
	 */
	public function __construct(?Buffer $buffer = null, public int $offset = 0)
	{
		$this->buffer = !is_null($buffer) ? $buffer : new Buffer();
	}

	/**
	 * @return void
	 */
	public function rewind(): void
	{
		$this->offset = 0;
	}

	/**
	 * @return void
	 */
	public function reset(): void
	{
		$this->buffer = new Buffer();
		$this->offset = 0;
	}

	/**
	 * @return bool
	 */
	public function eos(): bool
	{
		return $this->offset > $this->buffer->getLength() ? true : false;
	}

	/**
	 * @param Buffer $buffer
	 * @return void
	 */
	public function write(Buffer $buffer): void
	{
		$this->buffer->write($buffer);
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedByte(int $value): void
	{
		$this->write(new Buffer(pack("C", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeByte(int $value): void
	{
		$this->write(new Buffer(pack("c", $value)));
	}

	/**
	 * @param bool $value
	 * @return void
	 */
	public function writeBool(bool $value): void
	{
		$this->writeUnsignedByte($value === true ? 1 : 0);
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedShortBE(int $value): void
	{
		$this->write(new Buffer(pack("n", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeShortBE(int $value): void
	{
		$this->writeUnsignedShortBE(Buffer::toSignedShort($value));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedShortLE(int $value): void
	{
		$this->write(new Buffer(pack("v", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeShortLE(int $value): void
	{
		$this->writeUnsignedShortLE(Buffer::toSignedShort($value));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedTriadBE(int $value): void
	{
		$this->write(new Buffer(substr(pack("N", Buffer::limitUnsignedTriad($value)), 1)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeTriadBE(int $value): void
	{
		$this->write(new Buffer(substr(pack("N", Buffer::toSignedTriad($value)), 1)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedTriadLE(int $value): void
	{
		$this->write(new Buffer(substr(pack("V", Buffer::limitUnsignedTriad($value)), 0, -1)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeTriadLE(int $value): void
	{
		$this->write(new Buffer(substr(pack("V", Buffer::toSignedTriad($value)), 0, -1)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedIntBE(int $value): void
	{
		$this->write(new Buffer(pack("N", Buffer::limitUnsignedInt($value))));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeIntBE(int $value): void
	{
		$this->write(new Buffer(pack("N", Buffer::toSignedInt($value))));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedIntLE(int $value): void
	{
		$this->write(new Buffer(pack("V", Buffer::limitUnsignedInt($value))));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeIntLE(int $value): void
	{
		$this->write(new Buffer(pack("V", Buffer::toSignedInt($value))));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedLongBE(int $value): void
	{
		$this->write(new Buffer(pack("J", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeLongBE(int $value): void
	{
		$this->write(new Buffer(pack("J", Buffer::toSignedLongLong($value))));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeUnsignedLongLE(int $value): void
	{
		$this->write(new Buffer(pack("P", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeLongLE(int $value): void
	{
		$this->write(new Buffer(pack("P", Buffer::toSignedLongLong($value))));
	}

	/**
	 * @param float $value
	 * @return void
	 */
	public function writeFloat(float $value): void
	{
		$this->write(new Buffer(pack("G", $value)));
	}

	/**
	 * @param float $value
	 * @return void
	 */
	public function writeDouble(float $value): void
	{
		$this->write(new Buffer(pack("E", $value)));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeVarInt(int $value): void
	{
		for ($i = 0; $i < 5; ++$i)
		{
			$toWrite = $value & 0x7f;

			$value >>= 7;

			if ($value !== 0x00)
			{
				$this->writeUnsignedByte($toWrite | 0x80);
			}
			else
			{
				$this->writeUnsignedByte($toWrite);
				break;
			}
		}
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeSignedVarInt(int $value): void
	{
		$this->writeVarInt(($value << 1) ^ ($value >> 31));
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeVarLong(int $value): void
	{
		for ($i = 0; $i < 10; ++$i)
		{
			$toWrite = $value & 0x7f;

			$value >>= 7;

			if ($value !== 0x00)
			{
				$this->writeUnsignedByte($toWrite | 0x80);
			}
			else
			{
				$this->writeUnsignedByte($toWrite);
				break;
			}
		}
	}

	/**
	 * @param int $value
	 * @return void
	 */
	public function writeSignedVarLong(int $value): void
	{
		$this->writeVarLong(($value << 1) ^ ($value >> 63));
	}

	/**
	 * @param int $size
	 * @return Buffer
	 */
	public function read(int $size): Buffer
	{
		$this->offset += $size;
		return $this->buffer->read($this->offset - $size, $this->offset);
	}

	/**
	 * @return int
	 */
	public function readUnsignedByte(): int
	{
		return $this->read(1)->unpack("C")[1];
	}

	/**
	 * @return int
	 */
	public function readByte(): int
	{
		return $this->read(1)->unpack("c")[1];
	}

	/**
	 * @return bool
	 */
	public function readBool(): bool
	{
		return $this->readUnsignedByte() === 1 ? true : false;
	}

	/**
	 * @return int
	 */
	public function readUnsignedShortBE(): int
	{
		return $this->read(2)->unpack("n")[1];
	}

	/**
	 * @return int
	 */
	public function readShortBE(): int
	{
		return Buffer::toSignedShort($this->readUnsignedShortBE());
	}

	/**
	 * @return int
	 */
	public function readUnsignedShortLE(): int
	{
		return $this->read(2)->unpack("v")[1];
	}

	/**
	 * @return int
	 */
	public function readShortLE(): int
	{
		return Buffer::toSignedShort($this->readUnsignedShortLE());
	}

	/**
	 * @return int
	 */
	public function readUnsignedTriadBE(): int
	{
		return Buffer::limitUnsignedTriad($this->read(3)->unpack("N<")[1]);
	}

	/**
	 * @return int
	 */
	public function readTriadBE(): int
	{
		return Buffer::toSignedTriad($this->read(3)->unpack("N<")[1]);
	}

	/**
	 * @return int
	 */
	public function readUnsignedTriadLE(): int
	{
		return Buffer::limitUnsignedTriad($this->read(3)->unpack("V>")[1]);
	}

	/**
	 * @return int
	 */
	public function readTriadLE(): int
	{
		return Buffer::toSignedTriad($this->read(3)->unpack("V>")[1]);
	}

	/**
	 * @return int
	 */
	public function readUnsignedIntBE(): int
	{
		return Buffer::limitUnsignedInt($this->read(4)->unpack("N")[1]);
	}

	/**
	 * @return int
	 */
	public function readIntBE(): int
	{
		return Buffer::toSignedInt($this->read(4)->unpack("N")[1]);
	}

	/**
	 * @return int
	 */
	public function readUnsignedIntLE(): int
	{
		return Buffer::limitUnsignedInt($this->read(4)->unpack("V")[1]);
	}

	/**
	 * @return int
	 */
	public function readIntLE(): int
	{
		return Buffer::toSignedInt($this->read(4)->unpack("V")[1]);
	}

	/**
	 * @return int
	 */
	public function readUnsignedLongBE(): int
	{
		return $this->read(8)->unpack("J")[1];
	}

	/**
	 * @return int
	 */
	public function readLongBE(): int
	{
		return Buffer::toSignedLongLong($this->read(8)->unpack("J")[1]);
	}

	/**
	 * @return int
	 */
	public function readUnsignedLongLE(): int
	{
		return $this->read(8)->unpack("P")[1];
	}

	/**
	 * @return int
	 */
	public function readLongLE(): int
	{
		return Buffer::toSignedLongLong($this->read(8)->unpack("P")[1]);
	}

	/**
	 * @return float
	 */
	public function readFloat(): float
	{
		return $this->read(4)->unpack("G")[1];
	}

	/**
	 * @return float
	 */
	public function readDouble(): float
	{
		return $this->read(8)->unpack("E")[1];
	}

	/**
	 * @return int
	 * @throws RuntimeException
	 */
	public function readVarInt(): int
	{
		$value = 0;
		for ($i = 0; $i < 35; $i += 7)
		{
			$toRead = $this->readUnsignedByte();

			$value |= ($toRead & 0x7f) << $i;

			if (($toRead & 0x80) == 0x00)
			{
				return $value;
			}
		}

		throw new RuntimeException("VarInt is too big");
	}

	/**
	 * @return int
	 * @throws RuntimeException
	 */
	public function readSignedVarInt(): int
	{
		$value = $this->readVarInt();
		return ($value >> 1) ^ -($value & 1);
	}

	/**
	 * @return int
	 * @throws RuntimeException
	 */
	public function readVarLong(): int
	{
		$value = 0;
		for ($i = 0; $i < 70; $i += 7)
		{
			$toRead = $this->readUnsignedByte();

			$value |= ($toRead & 0x7f) << $i;

			if (($toRead & 0x80) == 0x00)
			{
				return $value;
			}
		}

		throw new RuntimeException("VarLong is too big");
	}

	/**
	 * @return int
	 * @throws RuntimeException
	 */
	public function readSignedVarLong(): int
	{
		$value = $this->readVarLong();
		return ($value >> 1) ^ -($value & 1);
	}

	/**
	 * @return Buffer
	 */
	public function readRemaining(): Buffer
	{
		return $this->read($this->buffer->getLength() - $this->offset);
	}
}