<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            // text / textarea / image / boolean / select
            $table->string('group')->default('general');
            // general / contact / social / seo / property / appearance
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            // General
            ['key' => 'site_name',        'value' => 'IndianestHub',            'type' => 'text',     'group' => 'general',    'label' => 'Site Name'],
            ['key' => 'site_tagline',     'value' => 'Find Your Dream Home',  'type' => 'text',     'group' => 'general',    'label' => 'Tagline'],
            ['key' => 'site_email',       'value' => 'info@indianesthub.com',    'type' => 'text',     'group' => 'general',    'label' => 'Site Email'],
            ['key' => 'site_phone',       'value' => '+91 98765 43210',       'type' => 'text',     'group' => 'general',    'label' => 'Site Phone'],
            ['key' => 'site_address',     'value' => 'Sector 17, Chandigarh', 'type' => 'textarea', 'group' => 'general',    'label' => 'Address'],
            ['key' => 'maintenance_mode', 'value' => '0',                    'type' => 'boolean',  'group' => 'general',    'label' => 'Maintenance Mode'],
            // Contact
            ['key' => 'whatsapp_number',  'value' => '+919876543210',        'type' => 'text',     'group' => 'contact',    'label' => 'WhatsApp Number'],
            ['key' => 'support_email',    'value' => 'support@indianesthub.com', 'type' => 'text',     'group' => 'contact',    'label' => 'Support Email'],
            ['key' => 'office_hours',     'value' => 'Mon–Sat, 10AM–7PM',    'type' => 'text',     'group' => 'contact',    'label' => 'Office Hours'],
            // Social
            ['key' => 'facebook_url',     'value' => '',                     'type' => 'text',     'group' => 'social',     'label' => 'Facebook URL'],
            ['key' => 'instagram_url',    'value' => '',                     'type' => 'text',     'group' => 'social',     'label' => 'Instagram URL'],
            ['key' => 'twitter_url',      'value' => '',                     'type' => 'text',     'group' => 'social',     'label' => 'Twitter / X URL'],
            ['key' => 'youtube_url',      'value' => '',                     'type' => 'text',     'group' => 'social',     'label' => 'YouTube URL'],
            ['key' => 'linkedin_url',     'value' => '',                     'type' => 'text',     'group' => 'social',     'label' => 'LinkedIn URL'],
            // SEO
            ['key' => 'meta_title',       'value' => 'IndianestHub — Find Property in Tricity', 'type' => 'text',     'group' => 'seo',      'label' => 'Meta Title'],
            ['key' => 'meta_description', 'value' => 'Buy, Sell & Rent properties in Chandigarh, Mohali, Zirakpur, Panchkula.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description'],
            ['key' => 'meta_keywords',    'value' => 'property, real estate, chandigarh, mohali, zirakpur', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Keywords'],
            ['key' => 'google_analytics', 'value' => '',                     'type' => 'text',     'group' => 'seo',        'label' => 'Google Analytics ID'],
            // Property display
            ['key' => 'properties_per_page',   'value' => '12',             'type' => 'text',     'group' => 'property',   'label' => 'Properties Per Page'],
            ['key' => 'featured_limit',         'value' => '6',              'type' => 'text',     'group' => 'property',   'label' => 'Featured Properties on Home'],
            ['key' => 'enable_loan_widget',     'value' => '1',              'type' => 'boolean',  'group' => 'property',   'label' => 'Show Loan Widget'],
            ['key' => 'enable_insurance_widget','value' => '1',              'type' => 'boolean',  'group' => 'property',   'label' => 'Show Insurance Widget'],
            // Appearance
            ['key' => 'primary_color',    'value' => '#0078d4',              'type' => 'text',     'group' => 'appearance', 'label' => 'Primary Color'],
            ['key' => 'footer_text',      'value' => '© 2026 IndianestHub. All rights reserved.', 'type' => 'textarea', 'group' => 'appearance', 'label' => 'Footer Text'],
            ['key' => 'copyright_year',   'value' => '2026',                 'type' => 'text',     'group' => 'appearance', 'label' => 'Copyright Year'],
        ];

        foreach ($defaults as $setting) {
            \DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
