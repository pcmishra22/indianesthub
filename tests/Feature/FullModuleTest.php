<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;

class FullModuleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_authentication_module()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ]);
        $response->assertStatus(302);
    }

    public function test_property_listings_module()
    {
        $response = $this->get('/properties');
        $response->assertStatus(200);
        $response->assertSee('Sample Property');
    }

    public function test_search_and_filtering_module()
    {
        $response = $this->get('/properties?city=City 1');
        $response->assertStatus(200);
    }

    public function test_map_integration_module()
    {
        $response = $this->get('/properties');
        $response->assertStatus(200);
        // Map integration placeholder
    }

    public function test_property_detail_page()
    {
        $property = \App\Models\Property::first();
        $response = $this->get('/properties/' . ($property->slug ?? $property->id));
        $response->assertStatus(200);
    }

    public function test_crm_lead_enquiry_module()
    {
        $property = \App\Models\Property::first();
        $response = $this->post('/property/inquiry/submit', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'message' => 'Interested in property',
            'property_id' => $property ? $property->id : 1,
        ]);
        $response->assertStatus(302);
    }

    public function test_agent_builder_module()
    {
        $response = $this->get('/agents');
        $response->assertStatus(200);
    }

    public function test_buyer_tenant_module()
    {
        $user = \App\Models\User::first();
        $this->actingAs($user);
        $response = $this->get('/my/wishlist');
        $response->assertStatus(200);
    }

    public function test_seller_lister_module()
    {
        $dealer = \App\Models\Dealer::first();
        $this->actingAs($dealer, 'dealer');
        $response = $this->get('/dealer/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_panel_module()
    {
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            $admin = \App\Models\Admin::create([
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('Password123!'),
            ]);
        }
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_featured_listings_module()
    {
        $response = $this->get('/properties?featured=1');
        $response->assertStatus(200);
    }

    public function test_ratings_reviews_module()
    {
        $response = $this->get('/reviews');
        $response->assertStatus(200);
    }

    public function test_content_cms_module()
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    public function test_notifications_module()
    {
        $response = $this->get('/notifications');
        $response->assertStatus(200);
    }

    public function test_payment_billing_module()
    {
        $response = $this->get('/wallet');
        $response->assertStatus(200);
    }

    public function test_chat_messaging_module()
    {
        $response = $this->get('/chat');
        $response->assertStatus(200);
    }

    public function test_property_comparison_module()
    {
        $response = $this->get('/compare?properties[]=1&properties[]=2');
        $response->assertStatus(200);
    }

    public function test_analytics_reporting_module()
    {
        $response = $this->get('/analytics');
        $response->assertStatus(200);
    }

    public function test_seo_marketing_module()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    public function test_security_compliance_module()
    {
        $response = $this->get('/privacy-policy');
        $response->assertStatus(200);
    }

    public function test_virtual_tours_ar()
    {
        $response = $this->get('/virtual-tour');
        $response->assertStatus(200);
    }

    public function test_ai_property_recommendations()
    {
        $response = $this->get('/recommendations');
        $response->assertStatus(200);
    }

    public function test_chatbot_for_leads()
    {
        $response = $this->get('/chatbot');
        $response->assertStatus(200);
    }

    public function test_property_market_insights()
    {
        $response = $this->get('/market-insights');
        $response->assertStatus(200);
    }

    public function test_dynamic_price_trends_chart()
    {
        $response = $this->get('/price-trends');
        $response->assertStatus(200);
    }

    public function test_multilingual_support()
    {
        $response = $this->get('/?lang=es');
        $response->assertStatus(200);
    }
}
