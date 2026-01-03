<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventPromotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_promo_and_redirect_increments_clicks()
    {
        $organizer = User::factory()->create(['role' => User::ROLE_ORGANIZER]);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);

        $this->actingAs($organizer)
            ->postJson(route('events.promotions.store', $event), [
                'label' => 'Test Campaign',
                'platform' => 'twitter',
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['url', 'token'])
            ->assertJsonCount(2);

        $promo = EventPromotion::first();
        $this->assertNotNull($promo);
        $this->assertEquals(0, $promo->clicks);

        $response = $this->get(route('promotions.redirect', $promo->token));
        $response->assertRedirect(route('events.show', $event));

        $promo->refresh();
        $this->assertEquals(1, $promo->clicks);
    }
}
