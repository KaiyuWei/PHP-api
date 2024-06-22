<?php

use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase
{
    public function returnTrue(): void
    {
        $this->assertTrue(true);
        $this->assertTrue(false);
    }
}