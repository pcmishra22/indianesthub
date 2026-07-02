<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Notification Service
 * Uses free WhatsApp API services (like CallMeBot, ChatAPI, etc.)
 * 
 * Configuration required in .env:
 * WHATSAPP_API_URL=https://api.callmebot.com/whatsapp.php
 * WHATSAPP_API_KEY=your_api_key
 * WHATSAPP_NUMBER=your_default_number
 */
class WhatsAppNotificationService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $defaultNumber;
    
    public function __construct()
    {
        $this->apiUrl = config('app.whatsapp_api_url', env('WHATSAPP_API_URL', 'https://api.callmebot.com/whatsapp.php'));
        $this->apiKey = config('app.whatsapp_api_key', env('WHATSAPP_API_KEY', ''));
        $this->defaultNumber = config('app.whatsapp_number', env('WHATSAPP_NUMBER', '7340753780'));
    }
    
    /**
     * Send WhatsApp message to a phone number
     * 
     * @param string $phoneNumber Receiver's WhatsApp number (with country code, e.g., 917340753780)
     * @param string $message Message to send
     * @return bool Success status
     */
    public function send(string $phoneNumber, string $message): bool
    {
        // Clean phone number - remove any non-digit characters except + 
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Skip if no valid API key configured
        if (empty($this->apiKey)) {
            Log::warning("WhatsApp API Key not configured. Message not sent.");
            return false;
        }
        
        try {
            $response = Http::timeout(30)->get($this->apiUrl, [
                'phone' => $phoneNumber,
                'text' => $message,
                'apikey' => $this->apiKey,
            ]);
            
            if ($response->successful()) {
                Log::info("WhatsApp notification sent successfully to {$phoneNumber}");
                return true;
            } else {
                Log::error("WhatsApp notification failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send WhatsApp notification to admin
     * 
     * @param string $message Message to send
     * @return bool Success status
     */
    public function sendToAdmin(string $message): bool
    {
        return $this->send($this->defaultNumber, $message);
    }
    
    /**
     * Send WhatsApp notification to dealer
     * 
     * @param string $phoneNumber Dealer's phone number
     * @param string $message Message to send
     * @return bool Success status
     */
    public function sendToDealer(string $phoneNumber, string $message): bool
    {
        return $this->send($phoneNumber, $message);
    }
    
    /**
     * Clean and format phone number
     * 
     * @param string $phoneNumber Raw phone number
     * @return string Cleaned phone number
     */
    private function cleanPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If number doesn't start with country code, assume India (91)
        if (strlen($cleaned) === 10) {
            $cleaned = '91' . $cleaned;
        }
        
        return $cleaned;
    }
    
    /**
     * Format inquiry notification message for admin
     * 
     * @param array $inquiryData Inquiry details
     * @return string Formatted message
     */
    public function formatAdminMessage(array $inquiryData): string
    {
        return "🔔 *New Property Inquiry*\n\n" .
            "Property: {$inquiryData['property_title']}\n" .
            "Name: {$inquiryData['name']}\n" .
            "Phone: {$inquiryData['phone']}\n" .
            "Email: {$inquiryData['email']}\n" .
            "Message: {$inquiryData['message']}";
    }
    
    /**
     * Format inquiry notification message for dealer
     * 
     * @param array $inquiryData Inquiry details
     * @return string Formatted message
     */
    public function formatDealerMessage(array $inquiryData): string
    {
        return "🔔 *New Inquiry for Your Property*\n\n" .
            "Property: {$inquiryData['property_title']}\n" .
            "Buyer: {$inquiryData['name']}\n" .
            "Phone: {$inquiryData['phone']}\n" .
            "Message: {$inquiryData['message']}\n\n" .
            "Reply via WhatsApp to connect!";
    }
}
