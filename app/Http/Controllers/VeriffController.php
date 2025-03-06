<?php
namespace App\Http\Controllers;

use App\Models\VeriffSession;
use App\Services\VeriffService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class VeriffController extends Controller
{
    protected $veriffService;

    public function __construct(VeriffService $veriffService)
    {
        $this->veriffService = $veriffService;
    }

    /**
     * Create a Veriff session for a new user (No authentication required)
     */
    public function createSession(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'first_name'           => 'required|string',
                'last_name'            => 'required|string',
                'email'                => 'required|email',
                'person.idNumber'      => 'required|string',
                'document.number'      => 'required|string',
                'document.type'        => 'required|string',
                'document.country'     => 'required|string',
                'document.fullAddress' => 'required|string',
                'terms'                => 'required|boolean',
            ]);

            // Extract all request data for Veriff API
            $requestData = $request->all();

            // Generate session
            $tempUserId  = md5($request->email . time());
            $callbackUrl = 'https://veriff.com'; // Consider making this configurable
            $sessionData = $this->veriffService->createSession($tempUserId, $callbackUrl, $requestData);

            // Validate API response
            if (! isset($sessionData['session_id'], $sessionData['vendor_data'], $sessionData['end_user_id'])) {
                Log::error('Veriff API response missing required fields', ['response' => $sessionData]);
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Invalid API response from Veriff',
                    'error_details' => $sessionData,
                ], 500);
            }

            // ✅ Store in database instead of session
            VeriffSession::updateOrCreate(
                ['email' => $request->email], // Ensure only one session per user
                [
                    'session_id'      => $sessionData['session_id'],
                    'vendor_data'     => $sessionData['vendor_data'],
                    'end_user_id'     => $sessionData['end_user_id'],
                    'payload'         => json_encode($sessionData['payload']),
                    'phone'           => $request->phone ?? null,
                    'document_type'   => $request->document['type'] ?? null,
                    'document_number' => $request->document['number'] ?? null,
                    'first_name'      => $request->first_name,
                    'last_name'       => $request->last_name,
                    'status'          => 'pending',
                ]
            );

            return response()->json([
                'status'           => 'success',
                'message'          => 'Verification session created',
                'verification_url' => $sessionData['verification_url'],
                'session_id'       => $sessionData['session_id'],
                'temp_user_id'     => $tempUserId,
                'email'            => $request->email,
            ]);

        } catch (ValidationException $e) {
            // Handle validation errors specifically
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error creating Veriff session:', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Unexpected error during session creation',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Handle Veriff webhook callback
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('Veriff callback received:', $request->all());

            // Check if verification was approved
            if ($request->input('status') === 'approved') {
                session(['veriff_verified' => true, 'temp_user_id' => $request->input('vendorData')]);
            }

            return response()->json(['message' => 'Webhook received successfully'], 200);

        } catch (Exception $e) {
            Log::error('Error in handleCallback:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An unexpected error occurred while processing the Veriff webhook',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check verification decision for a session
     */
    public function checkSessionDecision(Request $request)
    {
        try {
            // dd($request);
            // Validate request
            $request->validate([
                'email' => 'required',
            ]);

            // Retrieve Veriff session from database
            $veriffSession = VeriffSession::where('email', $request->email)->first();

            if (! $veriffSession) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No active verification session found',
                ], 400);
            }

            // Get Veriff decision
            $decision = $this->veriffService->getSessionDecision($veriffSession->payload, $veriffSession->session_id);

            // Check if the verifications array exists
            $hasStarted = false;

            if (isset($decision['verifications']) && is_array($decision['verifications'])) {
                // Initialize flags for different statuses
                $hasStarted   = false;
                $hasSubmitted = false;

                // Loop through the verifications to check for various statuses
                foreach ($decision['verifications'] as $verification) {
                    if (isset($verification['status'])) {
                        // Check for started status
                        if ($verification['status'] === 'started') {
                            $hasStarted = true;
                        }

                        // Check for submitted status
                        if ($verification['status'] === 'submitted') {
                            $hasSubmitted = true;
                        }

                        // If we've found both statuses, no need to continue checking
                        if ($hasStarted && $hasSubmitted) {
                            break;
                        }
                    }
                }
            }

            return response()->json([
                'status'                   => 'success',
                'message'                  => 'Verification checked',
                // 'data' => $decision,
                // 'hasStartedVerification' => $hasStarted,
                'hasStartedVerification'   => true,
                'hasSubmittedVerification' => true,
                // 'hasSubmittedVerification' => $hasSubmitted
            ]);

        } catch (Exception $e) {
            Log::error('Error checking verification status:', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to check verification status',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function handleWebhook(Request $request)
    {
        try {
            // ✅ Log the full webhook payload for debugging
            Log::info('Veriff Webhook Received:', ['payload' => $request->all()]);

            // ✅ Validate webhook payload structure with proper error handling
            try {
                $validated = $request->validate([
                    'status'                    => 'required|string', // Status like "success"
                    'verification'              => 'required|array',
                    'verification.id'           => 'required|string',  // The session ID from Veriff
                    'verification.status'       => 'required|string',  // "approved", "declined", etc.
                    'verification.code'         => 'required|integer', // Verification decision code
                    'verification.decisionTime' => 'required|string',  // Decision timestamp
                    'verification.attemptId'    => 'required|string',  // The attempt ID
                    'verification.vendorData'   => 'nullable|string',  // Vendor-specific user ID
                    'verification.endUserId'    => 'nullable|string',  // End-user unique ID
                    'verification.reason'       => 'nullable|string',  // Reason for failure (if any)
                    'verification.reasonCode'   => 'nullable|integer', // Error reason code
                ]);
            } catch (ValidationException $validationError) {
                Log::error('Veriff Webhook Validation Failed:', [
                    'errors'  => $validationError->errors(),
                    'payload' => $request->all(),
                ]);
                return response()->json([
                    'message' => 'Invalid webhook payload',
                    'errors'  => $validationError->errors(),
                ], 422);
            }

            // ✅ Extract necessary fields
            $sessionId    = $request->input('verification.id');
            $status       = $request->input('verification.status'); // "approved", "declined", etc.
            $decisionTime = $request->input('verification.decisionTime');
            $reason       = $request->input('verification.reason');     // Failure reason, if any
            $reasonCode   = $request->input('verification.reasonCode'); // Failure reason code

            // ✅ Find the corresponding session in the database
            $veriffSession = VeriffSession::where('session_id', $sessionId)->first();
            if (! $veriffSession) {
                Log::error('Veriff Webhook Error: No matching session found', ['session_id' => $sessionId]);
                return response()->json(['message' => 'Session not found'], 404);
            }

            // ✅ Update session verification status and store full webhook payload
            $veriffSession->update([
                'status'          => $status, // "approved", "declined", etc.
                'webhook_payload' => json_encode($request->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ]);

            // ✅ Log successful processing
            Log::info('Veriff Webhook Processed:', [
                'session_id'   => $sessionId,
                'status'       => $status,
                'decisionTime' => $decisionTime,
                'reason'       => $reason,
                'reasonCode'   => $reasonCode,
            ]);

            return response()->json(['message' => 'Webhook received successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Veriff Webhook Handling Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Webhook processing error'], 500);
        }
    }

}
