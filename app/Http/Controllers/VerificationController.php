<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    public function verifyAccount(Request $request)
    {
        $verifyStatus = true; // This would be determined based on your actual verification logic

        // return response()->json([
        //     'verifyStatus' => $verifyStatus,
        // ]);

        // Simulate verification logic; return true if verification is successful
        $email = "johndoe@example.com";
        $userDataw = [
            'user_id'    => uniqid(`user_id_{$email}`, true), // Use authenticated user ID or generate a unique one
                                                              // 'user_id' => 'unique_user_id_12345',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'johndoe@example.com',
            'phone'      => '+1234567890',
            'address'    => [
                'street'      => '123 Main St',
                'city'        => 'New York',
                'region'      => 'NY',
                'postal_code' => '10001',
                'country'     => 'US',
            ],
        ];

        $userData = [
            'user_id'    => uniqid(`user_id_{$email}`, true),
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john.doe@example.com',
            'phone'      => '+1234567890',
            'dob'        => '1990-01-01',
            'address'    => [
                'street'      => '123 Main St',
                'city'        => 'New York',
                'region'      => 'NY',
                'postal_code' => '10001',
                'country'     => 'US',
            ],
        ];

        $result = $this->createIdentityVerification($userData);

        if ($result['success']) {
            return response()->json([
                'success'      => true,
                'verifyStatus' => true,
                'message'      => $result['message'],
                'data'         => $result['data'] ?? null, // Include additional data if available
                'api_result'   => $result,                 // Include the full API response
                'data_res'     => $result['data_res'],     // Include the full API response
            ]);
        } else {
            return response()->json([
                'success'      => false,
                'verifyStatus' => false,
                'message'      => $result['message'],
                'error'        => $result['error'] ?? null, // Include error details if available
                'api_result'   => $result,                  // Include the full API response
                'data_res'     => $result['data_res'],      // Include the full API response
            ], 400);                                    // Optionally set a 400 Bad Request status for failure
        }

        // return response()->json([
        //     'verifyStatus' => $verifyStatus,
        // ]);
    }

    public function createIdentityVerification($userData)
{
        $plaidClientId    = config('services.plaid.client_id');
        $plaidSecret      = config('services.plaid.secret');
        $plaidEnvironment = config('services.plaid.environment'); // sandbox, development, or production

        $url = "https://{$plaidEnvironment}.plaid.com/identity_verification/create";

        try {
            $response = Http::post($url, [
                'client_id'      => $plaidClientId,
                'secret'         => $plaidSecret,
                'client_user_id' => $userData['user_id'], // Unique user ID
                'template_id'    => 'your_template_id',   // Plaid Template ID
                'is_shareable'   => true,
                'gave_consent'   => true,
                'user'           => [
                    'email_address' => $userData['email'],
                    'phone_number'  => $userData['phone'],
                    'date_of_birth' => $userData['dob'],
                    'name'          => [
                        'given_name'  => $userData['first_name'],
                        'family_name' => $userData['last_name'],
                    ],
                    'address'       => [
                        'street'      => $userData['address']['street'],
                        'city'        => $userData['address']['city'],
                        'region'      => $userData['address']['region'],
                        'postal_code' => $userData['address']['postal_code'],
                        'country'     => $userData['address']['country'],
                    ],
                ],
            ]);

            $data_res = ["plaidClientId" => $plaidClientId, "plaidSecret" => $plaidSecret, "url" => $url];
            $result   = $response->json();
            if ($response->successful()) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Identity verification created successfully.',
                //     'data' => $response->json(),
                // ]);

                return [
                    'success'  => true,
                    'message'  => 'User identity verified successfully with 100% confidence.',
                    'data'     => $result,
                    'data_res' => $data_res,
                ];
            } else {
                // return response()->json([
                //     'success' => false,
                //     'message' => $response->json('error_message'),
                //     'error' => $response->json(),
                // ], 400);

                return [
                    'success'  => false,
                    'message'  => 'User identity verification failed or not 100%.',
                    'data'     => $result,
                    'data_res' => $data_res,
                ];
            }
        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'An error occurred.',
            //     'error' => $e->getMessage(),
            // ], 500);

            return [
                'success' => false,
                'message' => 'An error occurred during Plaid API validation.',
                'error'   => $e->getMessage(),
            ];
        }
    }
    public function validateUserWithPlaid($userData)
{
        // Plaid API credentials
        $plaidClientId    = config('services.plaid.client_id');
        $plaidSecret      = config('services.plaid.secret');
        $plaidEnvironment = config('services.plaid.environment'); // 'sandbox', 'development', or 'production'

        // Plaid Identity Verification URL
        $url = "https://{$plaidEnvironment}.plaid.com/identity_verification/verify";

        try {
            // Make the API request
            $response = Http::post($url, [
                'client_id' => $plaidClientId,
                'secret'    => $plaidSecret,
                'user'      => [
                    'client_user_id' => $userData['user_id'], // Unique user ID
                ],
                'user_data' => [
                    'first_name'    => $userData['first_name'],
                    'last_name'     => $userData['last_name'],
                    'email_address' => $userData['email'],
                    'phone_number'  => $userData['phone'],
                    'address'       => [
                        'street'      => $userData['address']['street'],
                        'city'        => $userData['address']['city'],
                        'region'      => $userData['address']['region'], // State or province
                        'postal_code' => $userData['address']['postal_code'],
                        'country'     => $userData['address']['country'],
                    ],
                ],
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                $result = $response->json();

                // Check if verification is 100% complete
                if (isset($result['status']) && $result['status'] === 'verified') {
                    return [
                        'success' => true,
                        'message' => 'User identity verified successfully with 100% confidence.',
                        'data'    => $result,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'User identity verification failed or not 100%.',
                        'data'    => $result,
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Plaid API request failed.',
                    'error'   => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            // Handle any exceptions during the process
            return [
                'success' => false,
                'message' => 'An error occurred during Plaid API validation.',
                'error'   => $e->getMessage(),
            ];
        }
    }

}
