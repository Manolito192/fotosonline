<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeImageFile(): UploadedFile
    {
        return UploadedFile::fake()->image('foto.jpg', 800, 600);
    }

    public function test_admin_can_create_photo(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create([
            'slug' => 'naturaleza',
            'name_es' => 'Naturaleza',
            'name_en' => 'Nature',
        ]);

        $response = $this->actingAs($admin)->post('/admin/photos', [
            'category_id' => $category->id,
            'title' => 'Lago al amanecer',
            'slug' => 'lago-al-amanecer',
            'description' => 'Un lago al amanecer.',
            'price' => '19.99',
            'image' => $this->makeImageFile(),
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.photos.index'));

        $this->assertDatabaseHas('photos', [
            'title' => 'Lago al amanecer',
            'slug' => 'lago-al-amanecer',
            'price' => 19.99,
            'is_published' => true,
        ]);
    }

    public function test_guest_can_add_to_cart_and_place_order(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $category = Category::create(['slug' => 'ciudad', 'name_es' => 'Ciudad', 'name_en' => 'City']);

        $photo = Photo::create([
            'category_id' => $category->id,
            'title' => 'Calle estrecha',
            'slug' => 'calle-estrecha',
            'description' => null,
            'price' => 9.99,
            'image_path' => 'photos/calle.jpg',
            'original_path' => 'photos/originals/calle.jpg',
            'is_published' => true,
        ]);

        $this->post(route('cart.add', $photo));
        $this->get(route('cart.index'))->assertSee('Calle estrecha');

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Ana García',
            'customer_email' => 'ana@example.com',
            'notes' => 'Gracias',
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('checkout.confirmation', $order));
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(9.99, (float) $order->total);
        $this->assertSame(1, $order->items()->count());
        $this->assertNotNull($order->download_token);
    }

    public function test_download_requires_confirmed_order(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('local')->put('photos/originals/calle.jpg', 'fake-image-bytes');

        $category = Category::create(['slug' => 'ciudad', 'name_es' => 'Ciudad', 'name_en' => 'City']);

        $photo = Photo::create([
            'category_id' => $category->id,
            'title' => 'Calle estrecha',
            'slug' => 'calle-estrecha',
            'description' => null,
            'price' => 9.99,
            'image_path' => 'photos/calle.jpg',
            'original_path' => 'photos/originals/calle.jpg',
            'is_published' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-0001',
            'customer_name' => 'Ana García',
            'customer_email' => 'ana@example.com',
            'total' => 9.99,
            'status' => Order::STATUS_PENDING,
            'download_token' => 'token-secreto',
        ]);

        $order->items()->create([
            'photo_id' => $photo->id,
            'photo_title' => $photo->title,
            'photo_slug' => $photo->slug,
            'image_path' => $photo->image_path,
            'original_path' => $photo->original_path,
            'price' => $photo->price,
        ]);

        $url = route('downloads.item', [$order, $order->items->first()]).'?token=token-secreto';

        $this->get($url)->assertStatus(403);

        $order->update(['status' => Order::STATUS_CONFIRMED]);

        $response = $this->get($url);
        $response->assertOk();
        $this->assertStringContainsString('fake-image-bytes', $response->streamedContent());
    }

    public function test_admin_can_confirm_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-0002',
            'customer_name' => 'Ana García',
            'customer_email' => 'ana@example.com',
            'total' => 9.99,
            'status' => Order::STATUS_PENDING,
            'download_token' => 'token-secreto',
        ]);

        $this->actingAs($admin)->post(route('admin.orders.confirm', $order))
            ->assertRedirect();

        $this->assertSame(Order::STATUS_CONFIRMED, $order->fresh()->status);
    }
}
