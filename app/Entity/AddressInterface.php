<?php

namespace App\Entity;
interface AddressInterface
{
    public function getStreet(): string;
    public function getHouseNumber(): string;
    public function getZip(): string;
    public function getCity(): int;
}
