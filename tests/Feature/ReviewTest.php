<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Seller;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected $product;
    protected $seller;

    public function setUp(): void
    {
        parent::setUp();

        // Create a seller
        $this->seller = Seller::factory()->create(['status' => 'approved']);

        // Create a product belonging to the seller
        $this->product = Product::factory()->create([
            'seller_id' => $this->seller->seller_id,
        ]);
    }

    /**
     * Test that review can be created successfully
     * Verifies:
     * - Review created with UUID primary key (review_id)
     * - Uses product_id foreign key (representative column)
     */
    public function test_create_review_with_uuid_primary_key()
    {
        $reviewData = [
            'product_id' => $this->product->product_id,
            'name' => 'John Reviewer',
            'email' => 'reviewer@example.com',
            'province_id' => '12', // Valid province code
            'rating' => 5,
            'comment' => 'Excellent product!',
        ];

        $response = $this->postJson('/api/catalog/products/' . $this->product->product_id . '/reviews', $reviewData);

        $response->assertStatus(201);
        
        // Assert review created with UUID primary key
        $review = Review::where('email', 'reviewer@example.com')->first();
        $this->assertNotNull($review);
        $this->assertNotNull($review->review_id);
        $this->assertTrue(strlen($review->review_id) === 36); // UUID string format
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $review->review_id);
        
        // Assert review references product by product_id
        $this->assertEquals($this->product->product_id, $review->product_id);
        
        // Assert response includes review_id
        $response->assertJsonStructure([
            'review_id',
            'product_id',
            'email',
            'rating',
        ]);
    }

    /**
     * Test that duplicate email for same product is rejected (uniqueness constraint)
     */
    public function test_duplicate_email_for_product_rejected()
    {
        // Create first review
        $firstReview = Review::factory()->create([
            'product_id' => $this->product->product_id,
            'email' => 'duplicate@example.com',
        ]);

        // Attempt to create duplicate review with same email and product
        $reviewData = [
            'product_id' => $this->product->product_id,
            'name' => 'Duplicate Reviewer',
            'email' => 'duplicate@example.com',
            'province_id' => '12',
            'rating' => 4,
            'comment' => 'Another review',
        ];

        $response = $this->postJson('/api/catalog/products/' . $this->product->product_id . '/reviews', $reviewData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that same email can be used for different products
     */
    public function test_same_email_allowed_for_different_products()
    {
        // Create another product
        $anotherProduct = Product::factory()->create([
            'seller_id' => $this->seller->seller_id,
        ]);

        // Create review for first product
        Review::factory()->create([
            'product_id' => $this->product->product_id,
            'email' => 'shared@example.com',
        ]);

        // Create review for second product with same email
        $reviewData = [
            'product_id' => $anotherProduct->product_id,
            'name' => 'Same Reviewer',
            'email' => 'shared@example.com',
            'province_id' => '12',
            'rating' => 5,
            'comment' => 'Great!',
        ];

        $response = $this->postJson('/api/catalog/products/' . $anotherProduct->product_id . '/reviews', $reviewData);

        $response->assertStatus(201);
    }

    /**
     * Test that duplicate phone for same product is rejected (if phone provided)
     */
    public function test_duplicate_phone_for_product_rejected()
    {
        // Create first review with phone
        Review::factory()->create([
            'product_id' => $this->product->product_id,
            'phone' => '081234567890',
        ]);

        // Attempt to create duplicate review with same phone and product
        $reviewData = [
            'product_id' => $this->product->product_id,
            'name' => 'Phone Reviewer',
            'email' => 'phone@example.com',
            'phone' => '081234567890',
            'province_id' => '12',
            'rating' => 3,
            'comment' => 'OK product',
        ];

        $response = $this->postJson('/api/catalog/products/' . $this->product->product_id . '/reviews', $reviewData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test that review can reference product by product_id
     */
    public function test_review_foreign_key_references_product_id()
    {
        $review = Review::factory()->create([
            'product_id' => $this->product->product_id,
        ]);

        // Verify the review's product_id matches the product's product_id
        $this->assertEquals($this->product->product_id, $review->product_id);
        
        // Verify we can access product through relationship
        $this->assertNotNull($review->product);
        $this->assertEquals($this->product->product_id, $review->product->product_id);
    }

    /**
     * Test validation for invalid product_id
     */
    public function test_review_creation_fails_with_invalid_product_id()
    {
        $reviewData = [
            'product_id' => 'invalid-uuid',
            'name' => 'Reviewer',
            'email' => 'test@example.com',
            'province_id' => '12',
            'rating' => 5,
            'comment' => 'Test',
        ];

        $response = $this->postJson('/api/catalog/products/invalid-uuid/reviews', $reviewData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_id']);
    }
}
