<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create QRIS payment transaction
     */
    public function createQrisPayment($orderData)
    {
        Log::info('=== MIDTRANS QRIS SERVICE START ===', [
            'input_data' => $orderData,
            'timestamp' => now(),
        ]);

        try {
            // Set Midtrans configuration
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production', false);
            
            Log::info('Midtrans Configuration', [
                'server_key_set' => !empty($serverKey),
                'server_key_length' => strlen($serverKey ?? ''),
                'is_production' => $isProduction,
                'is_sanitized' => config('midtrans.is_sanitized'),
                'is_3ds' => config('midtrans.is_3ds'),
            ]);

            Config::$serverKey = $serverKey;
            Config::$isProduction = $isProduction;
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);

            // Ensure amount is properly formatted
            $grossAmount = (int) $orderData['amount'];
            
            Log::info('Midtrans Amount Processing', [
                'original_amount' => $orderData['amount'],
                'gross_amount' => $grossAmount,
                'amount_type' => gettype($orderData['amount']),
            ]);
            
            // Prepare item details - ensure total matches gross_amount
            $itemDetails = [];
            if (isset($orderData['items']) && is_array($orderData['items'])) {
                Log::info('Processing Item Details', [
                    'items_count' => count($orderData['items']),
                    'items_raw' => $orderData['items']
                ]);

                // Validate each item detail
                foreach ($orderData['items'] as $index => $item) {
                    Log::info("Processing Item #{$index}", [
                        'item_data' => $item,
                        'has_id' => isset($item['id']),
                        'has_price' => isset($item['price']),
                        'has_quantity' => isset($item['quantity']),
                        'has_name' => isset($item['name']),
                    ]);

                    if (isset($item['id'], $item['price'], $item['quantity'], $item['name']) &&
                        !empty(trim($item['id'])) && 
                        !empty(trim($item['name'])) &&
                        $item['price'] > 0 && 
                        $item['quantity'] > 0) {
                        
                        $processedItem = [
                            'id' => trim($item['id']),
                            'price' => (int) $item['price'],
                            'quantity' => (int) $item['quantity'],
                            'name' => trim($item['name'])
                        ];

                        $itemDetails[] = $processedItem;

                        Log::info("Item #{$index} Added", [
                            'processed_item' => $processedItem
                        ]);
                    } else {
                        Log::warning("Item #{$index} Skipped - Invalid Data", [
                            'item' => $item,
                            'validation_failed' => [
                                'empty_id' => empty(trim($item['id'] ?? '')),
                                'empty_name' => empty(trim($item['name'] ?? '')),
                                'invalid_price' => ($item['price'] ?? 0) <= 0,
                                'invalid_quantity' => ($item['quantity'] ?? 0) <= 0,
                            ]
                        ]);
                    }
                }
            }
            
            // If no valid items, create default item
            if (empty($itemDetails)) {
                $defaultItem = [
                    'id' => 'ORDER-ITEM-1',
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Order Payment'
                ];
                $itemDetails = [$defaultItem];

                Log::info('No Valid Items - Using Default Item', [
                    'default_item' => $defaultItem
                ]);
            }
            
            // Verify total amount matches
            $calculatedTotal = 0;
            foreach ($itemDetails as $item) {
                $calculatedTotal += $item['price'] * $item['quantity'];
            }

            Log::info('Amount Verification', [
                'gross_amount' => $grossAmount,
                'calculated_total' => $calculatedTotal,
                'items_for_calculation' => $itemDetails,
                'amounts_match' => $calculatedTotal === $grossAmount
            ]);
            
            if ($calculatedTotal !== $grossAmount) {
                Log::warning('Item details total mismatch - Adjusting', [
                    'gross_amount' => $grossAmount,
                    'calculated_total' => $calculatedTotal,
                    'difference' => $grossAmount - $calculatedTotal,
                    'items_before_adjustment' => $itemDetails
                ]);
                
                // Adjust the first item to match total
                if (!empty($itemDetails)) {
                    $itemDetails[0]['price'] = $grossAmount;
                    $itemDetails[0]['quantity'] = 1;
                    $itemDetails[0]['name'] = 'Order Payment (Adjusted)';
                    $itemDetails = [$itemDetails[0]]; // Keep only first item

                    Log::info('Items Adjusted', [
                        'adjusted_items' => $itemDetails
                    ]);
                }
            }

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderData['order_id'],
                'gross_amount' => $grossAmount
            ];

            Log::info('Midtrans Transaction Details', [
                'transaction_details' => $transactionDetails
            ]);

            // Prepare customer details
            $customerDetails = [
                'first_name' => $orderData['customer_name'] ?? 'Customer',
                'email' => $orderData['customer_email'] ?? 'customer@example.com',
                'phone' => $orderData['customer_phone'] ?? '081234567890'
            ];

            Log::info('Midtrans Customer Details', [
                'customer_details' => $customerDetails
            ]);

            Log::info('Midtrans Final Item Details', [
                'items' => $itemDetails,
                'items_count' => count($itemDetails),
                'total_amount_from_items' => array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $itemDetails))
            ]);
        
            $params = [
                'payment_type' => 'qris',
                'transaction_details' => $transactionDetails,
                'qris' => [
                    'acquirer' => 'gopay'
                ],
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'custom_expiry' => [
                    'order_time' => date('Y-m-d H:i:s O'),
                    'expiry_duration' => 15,
                    'unit' => 'minute'
                ]
            ];

            Log::info('Midtrans Request Parameters', [
                'params' => $params
            ]);

            // Log the parameters being sent to Midtrans
            Log::info('Calling Midtrans CoreApi::charge()...', [
                'order_id' => $params['transaction_details']['order_id'],
                'gross_amount' => $params['transaction_details']['gross_amount'],
                'items_count' => count($params['item_details']),
                'customer' => $params['customer_details']['first_name'],
            ]);
            
            $response = CoreApi::charge($params);
            
            Log::info('Midtrans QRIS Response Received', [
                'response_type' => gettype($response),
                'status_code' => $response->status_code ?? 'unknown',
                'status_message' => $response->status_message ?? 'unknown',
                'transaction_id' => $response->transaction_id ?? 'unknown',
                'transaction_status' => $response->transaction_status ?? 'unknown',
                'has_actions' => isset($response->actions),
                'actions_count' => isset($response->actions) ? count($response->actions) : 0,
                'qr_url' => isset($response->actions[0]->url) ? $response->actions[0]->url : 'no_url',
                'full_response' => $response
            ]);

            if (isset($response->actions[0]->url)) {
                Log::info('QRIS Payment Created Successfully', [
                    'order_id' => $orderData['order_id'],
                    'qr_code_url' => $response->actions[0]->url,
                    'transaction_id' => $response->transaction_id ?? null,
                ]);

                return [
                    'success' => true,
                    'data' => $response,
                    'qr_code_url' => $response->actions[0]->url,
                    'redirect_url' => $response->actions[0]->url,
                    'transaction_id' => $response->transaction_id ?? null
                ];
            } else {
                Log::error('Midtrans Response Missing QR URL', [
                    'response' => $response,
                    'order_id' => $orderData['order_id']
                ]);

                return [
                    'success' => false,
                    'error' => 'No QR code URL in Midtrans response',
                    'response' => $response
                ];
            }
            
        } catch (Exception $e) {
            Log::error('Midtrans Service Exception', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'order_id' => $orderData['order_id'] ?? 'unknown',
                'input_data' => $orderData,
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ];
        } finally {
            Log::info('=== MIDTRANS QRIS SERVICE END ===');
        }
    }

    /**
     * Handle callback from Midtrans
     */
    public function handleCallback($requestData)
    {
        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id;
            $fraud = $notification->fraud_status;

            $response = [
                'order_id' => $order_id,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'fraud_status' => $fraud
            ];

            if ($transaction == 'settlement' || $transaction == 'capture') {
                $response['status'] = 'success';
            } else if ($transaction == 'pending') {
                $response['status'] = 'pending';
            } else if ($transaction == 'deny') {
                $response['status'] = 'failed';
            } else if ($transaction == 'expire') {
                $response['status'] = 'expired';
            } else if ($transaction == 'cancel') {
                $response['status'] = 'cancelled';
            }

            return $response;
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}