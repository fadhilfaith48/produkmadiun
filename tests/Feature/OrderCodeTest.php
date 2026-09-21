<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_kode_order_mengikuti_format_yang_diharapkan(): void
    {
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[A-Z0-9]{8}$/', Order::generateCode());
    }

    public function test_kode_order_yang_dibangkitkan_unik(): void
    {
        $codes = [];
        for ($i = 0; $i < 200; $i++) {
            $codes[] = Order::generateCode();
        }

        $this->assertCount(200, array_unique($codes));
    }
}