<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthDuplicateTest extends TestCase
{
    use RefreshDatabase;

    private function basePayload(): array
    {
        return [
            'name' => 'Dup Owner',
            'email' => 'dup1@example.com',
            'password' => 'password123',
            'store_name' => 'Dup Store',
            'store_description' => 'Desc',
            'pic_name' => 'Dup PIC',
            'pic_phone' => '081122334455',
            'address' => 'Jl Dup',
            'rt' => '01',
            'rw' => '02',
            'province_id' => '12',
            'city_id' => '1201',
            'district_id' => '120101',
            'village_id' => '1201010001',
            'ktp_number' => '1111222233334444',
            'pic_image' => $this->createImage('pic.jpg'),
            'ktp_file' => $this->createImage('ktp.jpg'),
        ];
    }

    public function test_duplicate_store_name_is_rejected()
    {
        $payload = $this->basePayload();
        $this->post('/api/auth/register', $payload)->assertStatus(201);

        // Attempt to register another seller with same store_name but different email
        $payload2 = $this->basePayload();
        $payload2['email'] = 'dup2@example.com';
        $payload2['pic_phone'] = '082233445566';
        $payload2['ktp_number'] = '2222333344445555';

        $response = $this->post('/api/auth/register', $payload2);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['store_name']);
    }

    public function test_duplicate_ktp_number_is_rejected()
    {
        $payload = $this->basePayload();
        $this->post('/api/auth/register', $payload)->assertStatus(201);

        $payload2 = $this->basePayload();
        $payload2['email'] = 'dup3@example.com';
        $payload2['store_name'] = 'Another Store';
        $payload2['pic_phone'] = '082299001122';

        // reuse ktp_number
        $payload2['ktp_number'] = $payload['ktp_number'];

        $response = $this->post('/api/auth/register', $payload2);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ktp_number']);
    }

    public function test_duplicate_pic_phone_is_rejected()
    {
        $payload = $this->basePayload();
        $this->post('/api/auth/register', $payload)->assertStatus(201);

        $payload2 = $this->basePayload();
        $payload2['email'] = 'dup4@example.com';
        $payload2['store_name'] = 'Store Four';
        $payload2['ktp_number'] = '3333444455556666';

        // reuse pic_phone
        $payload2['pic_phone'] = $payload['pic_phone'];

        $response = $this->post('/api/auth/register', $payload2);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pic_phone']);
    }

    /**
     * Helper to create a minimal test image file
     */
    private function createImage($name)
    {
        $path = storage_path('tmp/' . $name);
        if (!is_dir(storage_path('tmp'))) {
            mkdir(storage_path('tmp'), 0755, true);
        }

        $img = imagecreatetruecolor(50, 50);
        imagefilledrectangle($img, 0, 0, 50, 50, imagecolorallocate($img, 255, 255, 255));
        imagejpeg($img, $path);
        imagedestroy($img);

        return new \Illuminate\Http\UploadedFile($path, $name, 'image/jpeg', null, true);
    }
}
