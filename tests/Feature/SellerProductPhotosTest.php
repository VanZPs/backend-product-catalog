<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Seller;
use App\Models\User;

class SellerProductPhotosTest extends TestCase
{
    use RefreshDatabase;

    private function createImage($name)
    {
        $path = storage_path('tmp/' . $name);
        if (!is_dir(storage_path('tmp'))) mkdir(storage_path('tmp'), 0755, true);
        $img = imagecreatetruecolor(50, 50);
        imagefilledrectangle($img, 0, 0, 50, 50, imagecolorallocate($img, 255, 255, 255));
        imagejpeg($img, $path);
        imagedestroy($img);
        return new \Illuminate\Http\UploadedFile($path, $name, 'image/jpeg', null, true);
    }

    public function test_create_product_requires_minimum_two_images()
    {
        $user = User::factory()->create(['role' => 'seller']);
        $seller = Seller::factory()->create(['user_id' => $user->user_id, 'status' => 'approved']);

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => 'P with one image',
            'price' => 10000,
            'stock' => 10,
            'images' => [ $this->createImage('a.jpg') ],
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/dashboard/seller/products', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images']);
    }

    public function test_create_product_sets_primary_image_when_two_or_more_images()
    {
        $user = User::factory()->create(['role' => 'seller']);
        $seller = Seller::factory()->create(['user_id' => $user->user_id, 'status' => 'approved']);

        $this->actingAs($user, 'sanctum');

        $payload = [
            'name' => 'P with two images',
            'price' => 15000,
            'stock' => 5,
            'images' => [ $this->createImage('a.jpg'), $this->createImage('b.jpg') ],
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/dashboard/seller/products', $payload);
        $response->assertStatus(201);
        $response->assertJsonStructure(['message','product' => ['product_id','primary_image','images']]);

        $productId = $response->json('product.product_id');
        $this->assertNotNull($response->json('product.primary_image'));
    }
}
