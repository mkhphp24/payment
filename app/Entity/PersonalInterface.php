<?php

namespace App\Entity;
interface PersonalInterface
{
    public function getFirstname(): string;
    public function getLastname(): string;
    public function getTelephone(): string;
}
