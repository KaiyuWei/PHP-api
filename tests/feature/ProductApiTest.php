<?php

use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase
{
    public function test_return_true(): void
    {
        $output = false;

        if(1===1) $output = true;
        $this->assertTrue($output);
    }
}