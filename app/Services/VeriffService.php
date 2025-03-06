<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;

class VeriffService
{
    protected $apiKey;
    // protected $apiKey = "b5cc8d53-5bac-4d47-a378-59bcf3b2cf8d";
    protected $baseUrl;
    protected $secretKey;
    // protected $baseUrl = 'https://stationapi.veriff.com/v1/';


    public function __construct()
    {
        $this->apiKey = config('services.veriff.api_key');
        $this->baseUrl = config('services.veriff.base_url');
        $this->secretKey = config('services.veriff.secret_key'); // Add this in .env
    }

    /**
     * Create a new Veriff session
     */
    public function createSession($userId, $callbackUrl, $data)
    {
        try {
            // Generate UUID and random vendor data
            $endUserId = Str::uuid()->toString();
            $vendorData = Str::random(32);
    
            // Prepare request body using passed data
            $requestBody = [
                'verification' => [
                    'callback' => $callbackUrl,
                    'person' => [
                        'firstName' => $data['first_name'] ?? 'John',
                        'lastName' => $data['last_name'] ?? 'Doe',
                        'idNumber' => $data['person']['idNumber'] ?? '123456789',
                        'gender' => 'male', // Consider making this dynamic in the future
                    ],
                    'document' => [
                        'number' => $data['document']['number'] ?? 'B01234567',
                        'type' => $data['document']['type'] ?? 'PASSPORT',
                        'country' => $data['document']['country'] ?? 'EE'
                    ],
                    'address' => [
                        'fullAddress' => $data['document']['fullAddress'] ?? 'Lorem Ipsum 30, 13612 Tallinn, Estonia'
                    ],
                    'vendorData' => $vendorData,
                    'endUserId' => $endUserId,
                    'consents' => [
                        [
                            'type' => 'ine',
                            'approved' => $data['terms'] ?? false
                        ]
                    ]
                ]
            ];
    
            // Log the request
            Log::info('Sending Veriff API Request:', ['body' => $requestBody]);
    
            // Send request to Veriff API
            $response = Http::withHeaders([
                'X-AUTH-CLIENT' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'sessions', $requestBody);
    
            // Convert response to JSON
            $responseData = $response->json() ?? [];
    
            // Log API response
            Log::info('Veriff API Response:', ['response' => $response->json()]);
    
            // Validate API response
            if (!isset($responseData['verification']['url'], $responseData['verification']['id'])) {
                Log::error('Invalid API response:', ['response' => $responseData]);
                return [
                    'status' => 'error',
                    'message' => 'Invalid API response from Veriff',
                    'response' => $responseData
                ];
            }
    
            // Return structured data
            return [
                'status' => 'success',
                'verification_url' => $responseData['verification']['url'],
                'session_id' => $responseData['verification']['id'],
                'vendor_data' => $vendorData,
                'payload' => $responseData,
                'end_user_id' => $endUserId
            ];
    
        } catch (Exception $e) {
            Log::error('Veriff API Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return ['status' => 'error', 'message' => 'API request failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Get verification session status
     */
     /**
     * Check the verification status of a session
     */
    public function getSessionDecision($payload, $sessionId)
    {
        try {
            // Generate HMAC-SHA256 signature using only the session ID, not the payload
            $hmacSignature = hash_hmac('sha256', $sessionId, $this->secretKey);
            $hmacSignature = strtolower($hmacSignature);
    
            // API URL
            $url = "{$this->baseUrl}sessions/{$sessionId}/attempts";
    
            // Send GET request with correct headers
            $response = Http::withHeaders([
                'X-AUTH-CLIENT' => $this->apiKey,
                'X-HMAC-SIGNATURE' => $hmacSignature,
                'Content-Type' => 'application/json',
            ])->get($url);
    
            $responseData = $response->json() ?? [];
    
            // Log response for debugging
            Log::info('Veriff Session Decision Response:', [
                'response' => $responseData, 
                'hmacSignature' => $hmacSignature,
                'sessionId' => $sessionId
            ]);
    
            return $responseData;
    
        } catch (Exception $e) {
            Log::error('Error fetching Veriff session decision:', ['message' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Unable to fetch session decision',
                'error' => $e->getMessage()
            ];
        }
    }
}
