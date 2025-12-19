<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_products_page_loads_successfully(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    public function test_products_page_has_search(): void
    {
        $response = $this->get('/products?search=test');

        $response->assertStatus(200);
    }

    public function test_product_detail_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/products/invalid-product-slug-12345');

        $response->assertStatus(404);
    }
}
