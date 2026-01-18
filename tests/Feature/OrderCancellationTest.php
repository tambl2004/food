<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

test('user can cancel pending order', function () {
    // Create a user
    $user = User::factory()->create();
    
    // Create a product
    $product = Product::factory()->create(['stock' => 10]);
    
    // Create a pending order
    $order = Order::create([
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_email' => $user->email,
        'customer_phone' => '0123456789',
        'customer_address' => 'Test Address',
        'total_amount' => 100000,
        'status' => 'pending'
    ]);
    
    // Create order item
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'price' => 50000
    ]);
    
    // Authenticate user and cancel order
    $response = $this->actingAs($user)
        ->postJson("/orders/{$order->id}/cancel");
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công!'
        ]);
    
    // Check order status is updated
    $order->refresh();
    expect($order->status)->toBe('cancelled');
    expect($order->cancelled_at)->not->toBeNull();
    expect($order->cancel_reason)->toBe('Khách hàng hủy đơn hàng');
    
    // Check product stock is restored
    $product->refresh();
    expect($product->stock)->toBe(12); // 10 + 2
});

test('user cannot cancel non-pending order', function () {
    $user = User::factory()->create();
    
    $order = Order::create([
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_email' => $user->email,
        'customer_phone' => '0123456789',
        'customer_address' => 'Test Address',
        'total_amount' => 100000,
        'status' => 'processing' // Not pending
    ]);
    
    $response = $this->actingAs($user)
        ->postJson("/orders/{$order->id}/cancel");
    
    $response->assertStatus(400)
        ->assertJson([
            'success' => false,
            'message' => 'Chỉ có thể hủy đơn hàng đang chờ xử lý.'
        ]);
});

test('user cannot cancel other users order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $order = Order::create([
        'user_id' => $user1->id,
        'customer_name' => $user1->name,
        'customer_email' => $user1->email,
        'customer_phone' => '0123456789',
        'customer_address' => 'Test Address',
        'total_amount' => 100000,
        'status' => 'pending'
    ]);
    
    $response = $this->actingAs($user2)
        ->postJson("/orders/{$order->id}/cancel");
    
    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'Bạn không có quyền hủy đơn hàng này.'
        ]);
});
