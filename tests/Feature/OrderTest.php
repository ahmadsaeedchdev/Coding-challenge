<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * @var string
     */
    private  $orderUrl = '/api/v1/orders';


    public function test_ifOrderDataIsEmpty_get_422_statusCode()
    {
        $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, []);

        $response->assertStatus(422);

    }

    public function test_ifOrderCorrectPayloadSend_get_200_statusCode_and_orderSavedInDatabase()
    {
        $payload = [
            'products' => [
                [
                    'product_id' => Product::first()->id,
                    'quantity' => rand(1, 4)
                ]
            ]
        ];

        $user = $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, $payload);

        $response->assertOk();

        $order = Order::first();

        $this->assertEquals($user->id, $order->user_id);
    }


    public function test_ifProductIdIsMissingInPayload_get_422_statusCode()
    {
        $payload = [
            'products' => [
                [
                    'quantity' => rand(1, 4)
                ]
            ]
        ];

        $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, $payload);

        $response->assertUnprocessable();
    }

    public function test_ifQuantityIsMissingInPayload_get_422_statusCode()
    {
        $payload = [
            'products' => [
                [
                    'product_id' => Product::first(),
                ]
            ]
        ];

        $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, $payload);

        $response->assertUnprocessable();
    }

    public function test_ifIngredientCurrentAmountIsZero_get_400_statusCode_and_orderIsNotSavedInDatabase()
    {
        $product = Product::first();

        $ingredient = $product->ingredients->first();

        $ingredient->update(['current_amount' => 0]);

        $payload = [
            'products' => [
                [
                    'product_id' => Product::first()->id,
                    'quantity' => rand(1, 4)
                ]
            ]
        ];

        $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, $payload);

        $response->assertBadRequest();

        $this->assertNull(Order::first());
    }

    public function test_ifIngredientBelow50_then_fieldIsAlertUpdateInDatabase()
    {
        $product = Product::first();

        $ingredient = $product->ingredients->first();

        $ingredient->update(['current_amount' =>  $ingredient->current_amount/2]);

        $payload = [
            'products' => [
                [
                    'product_id' => Product::first()->id,
                    'quantity' => rand(1, 4)
                ]
            ]
        ];

        $this->getAuthenticatedUserAndSetTokenInHeader();

        $response = $this->postJson($this->orderUrl, $payload);

        $response->assertOk();

        $ingredient = Ingredient::find($ingredient->id);

        $this->assertEquals(1, $ingredient->is_alert_email_sent);

    }

    public function test_ifUserUnAuthenticated_get_401_statusCode()
    {
        $this->postJson($this->orderUrl, [], ['Content-Type' => 'application/json'])->assertStatus(401);
    }

}
