<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomerType;
use App\Models\MasterCustomers;
use Illuminate\Http\Request;


class ResellerPricingController extends Controller
{
    /**
     * Display reseller pricing system page
     */
    public function index()
    {
        // Static pricing data for reseller products
        $pricingData = [
            [
                'name' => 'Gentle Baby 10ml',
                'retail_price' => 36500,
                'normal_price' => 38500,
                'discount_price' => 26950,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby 30ml',
                'retail_price' => 87000,
                'normal_price' => 90000,
                'discount_price' => 63000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby 100ml',
                'retail_price' => 265000,
                'normal_price' => 275000,
                'discount_price' => 192500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby Twinpack',
                'retail_price' => 105000,
                'normal_price' => 110000,
                'discount_price' => 77000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Healo',
                'retail_price' => 23000,
                'normal_price' => 25000,
                'discount_price' => 17500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Healo Bundling',
                'retail_price' => 68000,
                'normal_price' => 70000,
                'discount_price' => 49000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Mamina ASI Booster 20 Teabag',
                'retail_price' => 62000,
                'normal_price' => 65000,
                'discount_price' => 45500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Mamina ASI Booster 10 Teabag',
                'retail_price' => 41000,
                'normal_price' => 43000,
                'discount_price' => 30100,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Breastfeeding Oil',
                'retail_price' => 108000,
                'normal_price' => 125000,
                'discount_price' => 87500,
                'discount_percentage' => 30,
            ],
        ];

        // Tiered discount based on purchase amount
        $tieredDiscounts = [
            [
                'min_purchase' => 1000000,
                'max_purchase' => 5000000,
                'discount_percentage' => 5,
                'description' => 'Pembelian Rp 1.000.000 - Rp 5.000.000'
            ],
            [
                'min_purchase' => 5000001,
                'max_purchase' => null,
                'discount_percentage' => 10,
                'description' => 'Pembelian di atas Rp 5.000.000'
            ]
        ];

        return view('admin.reseller-pricing.index', compact('pricingData', 'tieredDiscounts'));
    }
    
    /**
     * Calculate tiered discount based on purchase amount
     */
    private function calculateTieredDiscount($totalAmount)
    {
        if ($totalAmount >= 5000001) {
            return 10; // 10% discount
        } elseif ($totalAmount >= 1000000 && $totalAmount <= 5000000) {
            return 5; // 5% discount
        }
        return 0; // No discount
    }

    /**
     * Calculate margin percentage between two prices
     */
    private function calculateMargin($sellPrice, $costPrice)
    {
        if ($costPrice == 0) {
            return 0;
        }
        
        return (($sellPrice - $costPrice) / $costPrice) * 100;
    }

    /**
     * Get pricing statistics
     */
    public function getStatistics()
    {
        $pricingData = $this->getPricingData();
        
        $totalMargins = 0;
        $positiveMargins = 0;
        $negativeMargins = 0;
        $totalProducts = count($pricingData);
        $detailedStats = [];
        
        foreach ($pricingData as $product) {
            $normalMargin = $this->calculateMargin($product['normal_price'], $product['retail_price']);
            $discountMargin = $this->calculateMargin($product['discount_price'], $product['retail_price']);
            
            $totalMargins += $normalMargin;
            
            if ($discountMargin > 0) {
                $positiveMargins++;
            } else {
                $negativeMargins++;
            }
            
            $detailedStats[] = [
                'name' => $product['name'],
                'normal_margin' => $normalMargin,
                'discount_margin' => $discountMargin,
                'is_profitable_on_discount' => $discountMargin > 0,
            ];
        }
        
        $statistics = [
            'total_products' => $totalProducts,
            'average_margin' => $totalMargins / $totalProducts,
            'products_with_positive_discount_margin' => $positiveMargins,
            'products_with_negative_discount_margin' => $negativeMargins,
            'detailed_stats' => $detailedStats,
        ];
        
        return view('admin.reseller-pricing.statistics', compact('statistics'));
    }

    /**
     * Display reseller points system page
     */
    public function points()
    {
        // Points data for each product
        $pointsData = [
            [
                'name' => 'Gentle Baby 10ml',
                'category' => 'Gentle Baby',
                'points' => 1,
                'description' => 'Produk entry level dengan poin dasar'
            ],
            [
                'name' => 'Gentle Baby 30ml',
                'category' => 'Gentle Baby',
                'points' => 4,
                'description' => 'Ukuran populer dengan poin lebih tinggi'
            ],
            [
                'name' => 'Gentle Baby Twinpack',
                'category' => 'Gentle Baby',
                'points' => 5,
                'description' => 'Paket hemat dengan poin bonus'
            ],
            [
                'name' => 'Gentle Baby 100ml',
                'category' => 'Gentle Baby',
                'points' => 12,
                'description' => 'Ukuran terbesar dengan poin maksimal'
            ],
            [
                'name' => 'Healo 10ml',
                'category' => 'Healo',
                'points' => 1,
                'description' => 'Produk kesehatan dengan poin standar'
            ],
            [
                'name' => 'Healo Bundling',
                'category' => 'Healo',
                'points' => 4,
                'description' => 'Paket bundling dengan nilai lebih tinggi'
            ],
            [
                'name' => 'ASI Booster 20 Teabag',
                'category' => 'ASI Booster',
                'points' => 7,
                'description' => 'Paket lengkap dengan poin premium'
            ],
            [
                'name' => 'ASI Booster 10 Teabag',
                'category' => 'ASI Booster',
                'points' => 4,
                'description' => 'Paket trial dengan poin moderate'
            ]
        ];

        // Bonus rewards data
        $bonusRewards = [
            [
                'points_required' => 1000,
                'bonus_amount' => 500000,
                'level' => 'Bronze',
                'description' => 'Bonus pertama untuk reseller aktif'
            ],
            [
                'points_required' => 2500,
                'bonus_amount' => 1500000,
                'level' => 'Silver',
                'description' => 'Level silver dengan bonus menarik'
            ],
            [
                'points_required' => 5000,
                'bonus_amount' => 3500000,
                'level' => 'Gold',
                'description' => 'Level gold dengan bonus premium'
            ],
            [
                'points_required' => 7500,
                'bonus_amount' => 5000000,
                'level' => 'Platinum',
                'description' => 'Level platinum dengan bonus besar'
            ],
            [
                'points_required' => 10000,
                'bonus_amount' => 7500000,
                'level' => 'Diamond',
                'description' => 'Level diamond dengan bonus fantastis'
            ],
            [
                'points_required' => 12500,
                'bonus_amount' => 10000000,
                'level' => 'Master',
                'description' => 'Level master dengan bonus luar biasa'
            ],
            [
                'points_required' => 15000,
                'bonus_amount' => 12500000,
                'level' => 'Ultimate',
                'description' => 'Level ultimate dengan bonus maksimal'
            ]
        ];

        return view('admin.reseller-pricing.points', compact('pointsData', 'bonusRewards'));
    }

    /**
     * Display reseller purchases with points calculation
     */
    public function purchases()
    {
        // Get reseller customer type
        $resellerType = MasterCustomerType::where('name_customer_type', 'LIKE', '%reseller%')->first();
        
        // Get all reseller customers with their transactions
        $resellers = MasterCustomers::with(['transactionSales' => function($query) {
            $query->with('transactionSalesDetails.masterItem')
                  ->orderBy('date', 'desc');
        }])
        ->when($resellerType, function($query) use ($resellerType) {
            return $query->where('customer_type_id', $resellerType->customer_type_id);
        })
        ->orderBy('point', 'desc')
        ->get();

        // Calculate purchases data
        $purchasesData = [];
        foreach ($resellers as $reseller) {
            $totalPurchase = 0;
            $totalAfterDiscount = 0;
            $totalDiscountAmount = 0;
            $transactionCount = 0;
            $totalPoints = 0;
            $productsPurchased = [];
            
            foreach ($reseller->transactionSales as $transaction) {
                $transactionCount++;
                
                $transactionSubtotal = 0;
                $transactionProductDiscount = 0;
                
                // Calculate from transaction details to get actual discount
                foreach ($transaction->transactionSalesDetails as $detail) {
                    $productName = $detail->masterItem->name_item ?? 'Unknown Product';
                    $quantity = $detail->qty ?? 0;
                    $subtotal = $detail->subtotal ?? 0;
                    $discountAmount = $detail->discount_amount ?? 0;
                    
                    $transactionSubtotal += $subtotal;
                    $transactionProductDiscount += $discountAmount;
                    
                    // Determine points based on product name
                    $points = $this->calculateProductPoints($productName);
                    $totalPoints += ($points * $quantity);
                    
                    // Track products purchased
                    if (!isset($productsPurchased[$productName])) {
                        $productsPurchased[$productName] = [
                            'quantity' => 0,
                            'points' => $points,
                            'total_points' => 0
                        ];
                    }
                    $productsPurchased[$productName]['quantity'] += $quantity;
                    $productsPurchased[$productName]['total_points'] += ($points * $quantity);
                }
                
                // Add transaction-level discount
                $transactionDiscount = $transaction->discount_amount ?? 0;
                $totalTransactionDiscount = $transactionProductDiscount + $transactionDiscount;
                
                // Calculate transaction total
                $transactionTotal = $transactionSubtotal - $totalTransactionDiscount;
                
                $totalPurchase += $transactionSubtotal;
                $totalDiscountAmount += $totalTransactionDiscount;
                $totalAfterDiscount += $transactionTotal;
            }
            
            $purchasesData[] = [
                'customer_id' => $reseller->customer_id,
                'name' => $reseller->name_customer,
                'email' => $reseller->email_customer,
                'phone' => $reseller->phone_customer,
                'current_points' => $reseller->point ?? 0,
                'calculated_points' => $totalPoints,
                'total_purchase' => $totalPurchase,
                'total_after_discount' => $totalAfterDiscount,
                'discount_amount' => $totalDiscountAmount,
                'transaction_count' => $transactionCount,
                'last_transaction' => $reseller->transactionSales->first()?->date,
                'products_purchased' => $productsPurchased,
            ];
        }

        return view('admin.reseller-pricing.purchases', compact('purchasesData'));
    }
    
    /**
     * Calculate points based on product name
     */
    private function calculateProductPoints($productName)
    {
        $productName = strtolower($productName);
        
        // Points mapping based on product
        if (strpos($productName, 'gentle baby 100ml') !== false) {
            return 12;
        } elseif (strpos($productName, 'gentle baby twinpack') !== false) {
            return 5;
        } elseif (strpos($productName, 'gentle baby 30ml') !== false) {
            return 4;
        } elseif (strpos($productName, 'gentle baby 10ml') !== false) {
            return 1;
        } elseif (strpos($productName, 'asi booster 20') !== false || strpos($productName, 'mamina 20') !== false) {
            return 7;
        } elseif (strpos($productName, 'asi booster 10') !== false || strpos($productName, 'mamina 10') !== false) {
            return 4;
        } elseif (strpos($productName, 'healo bundling') !== false) {
            return 4;
        } elseif (strpos($productName, 'healo') !== false) {
            return 1;
        }
        
        // Default points
        return 1;
    }

    /**
     * Get customer transactions with details
     */
    public function getCustomerTransactions($customerId)
    {
        try {
            $customer = MasterCustomers::with(['transactionSales' => function($query) {
                $query->with(['transactionSalesDetails.masterItem'])
                      ->orderBy('date', 'desc');
            }])->findOrFail($customerId);

            $transactions = [];
            foreach ($customer->transactionSales as $transaction) {
                $details = [];
                foreach ($transaction->transactionSalesDetails as $detail) {
                    $details[] = [
                        'id' => $detail->transaction_sales_detail_id,
                        'product_name' => $detail->masterItem->name_item ?? 'Unknown Product',
                        'qty' => $detail->qty,
                        'sell_price' => $detail->sell_price,
                        'subtotal' => $detail->subtotal,
                        'discount_percentage' => $detail->discount_percentage ?? 0,
                        'discount_amount' => $detail->discount_amount ?? 0,
                        'total_amount' => $detail->total_amount ?? ($detail->subtotal - ($detail->discount_amount ?? 0))
                    ];
                }

                $transactions[] = [
                    'id' => $transaction->transaction_sales_id,
                    'number' => $transaction->number,
                    'date' => $transaction->date,
                    'details' => $details
                ];
            }

            return response()->json([
                'success' => true,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update discounts for transaction details
     */
    public function updateDiscounts(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|integer',
                'discounts' => 'required|array',
                'discounts.*.detail_id' => 'required|integer',
                'discounts.*.discount_percentage' => 'required|numeric|min:0|max:100',
                'discounts.*.discount_amount' => 'required|numeric|min:0',
                'discounts.*.total_amount' => 'required|numeric|min:0',
                'transaction_discounts' => 'sometimes|array',
                'transaction_discounts.*.transaction_id' => 'required_with:transaction_discounts|integer',
                'transaction_discounts.*.discount_percentage' => 'required_with:transaction_discounts|numeric|min:0|max:100',
                'transaction_discounts.*.discount_amount' => 'required_with:transaction_discounts|numeric|min:0'
            ]);

            $updatedCount = 0;
            
            // Update product-level discounts
            foreach ($request->discounts as $discount) {
                $detail = \App\Models\TransactionSalesDetails::find($discount['detail_id']);
                if ($detail) {
                    $detail->update([
                        'discount_percentage' => $discount['discount_percentage'],
                        'discount_amount' => $discount['discount_amount'],
                        'total_amount' => $discount['total_amount']
                    ]);
                    $updatedCount++;
                }
            }
            
            // Update transaction-level discounts
            if ($request->has('transaction_discounts')) {
                foreach ($request->transaction_discounts as $transactionDiscount) {
                    $transaction = \App\Models\TransactionSales::find($transactionDiscount['transaction_id']);
                    if ($transaction) {
                        $transaction->update([
                            'discount_percentage' => $transactionDiscount['discount_percentage'],
                            'discount_amount' => $transactionDiscount['discount_amount']
                        ]);
                    }
                }
            }

            // Recalculate transaction totals
            $this->recalculateTransactionTotals($request->customer_id);

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengupdate $updatedCount diskon produk dan diskon transaksi",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalculate transaction totals after discount updates
     */
    private function recalculateTransactionTotals($customerId)
    {
        $customer = MasterCustomers::with('transactionSales.transactionSalesDetails')->find($customerId);
        
        if ($customer) {
            foreach ($customer->transactionSales as $transaction) {
                $subtotal = 0;
                $totalProductDiscount = 0;
                
                // Calculate subtotal and product-level discounts
                foreach ($transaction->transactionSalesDetails as $detail) {
                    $subtotal += $detail->subtotal ?? 0;
                    $totalProductDiscount += $detail->discount_amount ?? 0;
                }
                
                // Get transaction-level discount
                $transactionDiscountPercentage = $transaction->discount_percentage ?? 0;
                $transactionDiscountAmount = $transaction->discount_amount ?? 0;
                
                // Calculate total after all discounts
                // Total = Subtotal - Product Discounts - Transaction Discount
                $totalAfterProductDiscounts = $subtotal - $totalProductDiscount;
                $totalAmount = $totalAfterProductDiscounts - $transactionDiscountAmount;
                
                // Update transaction with calculated values
                $transaction->update([
                    'subtotal' => $subtotal,
                    'total_amount' => $totalAmount
                ]);
            }
        }
    }

    /**
     * Get the pricing data (can be moved to a service or model later)
     */
    private function getPricingData()
    {
        return [
            [
                'name' => 'Gentle Baby 10ml',
                'retail_price' => 36500,
                'normal_price' => 38500,
                'discount_price' => 26950,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby 30ml',
                'retail_price' => 87000,
                'normal_price' => 90000,
                'discount_price' => 63000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby 100ml',
                'retail_price' => 265000,
                'normal_price' => 275000,
                'discount_price' => 192500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Gentle Baby Twinpack',
                'retail_price' => 105000,
                'normal_price' => 110000,
                'discount_price' => 77000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Healo',
                'retail_price' => 23000,
                'normal_price' => 25000,
                'discount_price' => 17500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Healo Bundling',
                'retail_price' => 68000,
                'normal_price' => 70000,
                'discount_price' => 49000,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Mamina ASI Booster 20 Teabag',
                'retail_price' => 62000,
                'normal_price' => 65000,
                'discount_price' => 45500,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Mamina ASI Booster 10 Teabag',
                'retail_price' => 41000,
                'normal_price' => 43000,
                'discount_price' => 30100,
                'discount_percentage' => 30,
            ],
            [
                'name' => 'Breastfeeding Oil',
                'retail_price' => 108000,
                'normal_price' => 125000,
                'discount_price' => 87500,
                'discount_percentage' => 30,
            ],
        ];
    }
}