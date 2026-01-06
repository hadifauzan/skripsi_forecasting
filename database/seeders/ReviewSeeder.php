<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterItem;
use App\Models\Payment;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample review comments berdasarkan rating
        $reviewTemplates = [
            5 => [
                "Produk sangat berkualitas! Sangat puas dengan pembelian ini. Recommended banget!",
                "Excellent product! Kualitas terbaik dan packaging rapi. Will order again!",
                "Produk original dan sesuai ekspektasi. Delivery cepat, seller responsif. Top!",
                "Luar biasa! Produk benar-benar berkualitas premium. Sangat merekomendasikan!",
                "Perfect! Exactly what I ordered. Quality sangat bagus dan worth the price.",
                "Produk mantap! Kualitas oke banget, packaging safety, fast delivery. Love it!",
                "Amazing quality! Produk sesuai deskripsi dan bahkan lebih bagus dari ekspektasi.",
                "Sangat puas! Produk berkualitas tinggi, pengiriman cepat. Seller terpercaya!",
            ],
            4 => [
                "Produk bagus, sesuai dengan deskripsi. Delivery agak lama tapi worth it.",
                "Good quality product. Packaging oke, cuma pengiriman sedikit delay.",
                "Produk berkualitas baik. Hanya saja kemasan kurang rapi, tapi produknya oke.",
                "Nice product! Kualitas sesuai harga. Packaging bisa diperbaiki sedikit.",
                "Produk good quality. Delivery on time. Minor issue dengan kemasan saja.",
                "Bagus! Produk sesuai ekspektasi. Cuma customer service agak lambat respon.",
                "Good purchase. Quality bagus, cuma size sedikit lebih kecil dari perkiraan.",
                "Produk oke! Kualitas baik, tapi pengiriman bisa lebih cepat lagi.",
            ],
            3 => [
                "Produk standard. Not bad but not exceptional either. Harga sesuai kualitas.",
                "Biasa saja. Produk sesuai deskripsi tapi tidak ada yang istimewa.",
                "Okay lah. Kualitas lumayan, pengiriman standard. Nothing special.",
                "Produk oke untuk harga segini. Ekspektasi tidak terlalu tinggi dan sesuai.",
                "Average quality. Produk berfungsi dengan baik tapi tidak ada wow factor.",
                "Lumayan. Produk sesuai gambar, cuma kualitas material bisa lebih bagus.",
                "Standard product. Delivery oke, packaging biasa aja. Overall acceptable.",
                "Biasa aja sih. Produk berfungsi tapi kualitas bisa ditingkatkan lagi.",
            ],
            2 => [
                "Produk kurang sesuai ekspektasi. Kualitas agak mengecewakan untuk harga segini.",
                "Not satisfied. Produk terlihat berbeda dari foto. Kualitas dibawah ekspektasi.",
                "Agak kecewa. Packaging buruk dan produk ada sedikit cacat.",
                "Below expectation. Delivery lama dan produk tidak sesuai deskripsi.",
                "Kurang puas. Kualitas produk biasa aja tapi harga cukup mahal.",
                "Disappointing. Produk ternyata lebih kecil dari perkiraan dan kualitas kurang.",
                "Not recommended. Customer service lambat dan produk ada defect.",
                "Agak menyesal beli. Kualitas tidak sebanding dengan harga yang dibayar.",
            ],
            1 => [
                "Sangat mengecewakan! Produk rusak saat sampai dan seller tidak responsif.",
                "Terrible experience! Produk palsu dan tidak sesuai dengan gambar sama sekali.",
                "Worst purchase ever! Delivery super lama dan produk completely damaged.",
                "Tidak recommended! Produk cacat, packaging rusak, customer service buruk.",
                "Very disappointing! Produk tidak berfungsi dan seller sulit dihubungi.",
                "Awful quality! Money wasted. Produk completely different dari yang diiklankan.",
                "Bad experience! Fake product, poor packaging, terrible customer service.",
                "Sangat tidak puas! Produk rusak dan proses return sangat sulit.",
            ],
        ];

        // Get existing data - Only orders with successful payments
        $users = User::all();
        $paidOrders = Order::whereHas('payments', function($query) {
                $query->where('transaction_status', 'settlement');
            })
            ->with(['orderItems.masterItem', 'payments'])
            ->where('status', 'delivered') // Only delivered orders can be reviewed
            ->get();

        $allProducts = MasterItem::where('status_item', 'active')->get();

        if ($users->isEmpty() || $paidOrders->isEmpty()) {
            $this->command->warn('No users or paid delivered orders found. Please run UserSeeder, OrderSeeder and PaymentSeeder first.');
            return;
        }

        $reviewCount = 0;
        
        // Clear existing reviews to avoid duplicates
        Review::truncate();
        $this->command->info('Cleared existing reviews to create fresh data linked to payments...');
        
        // Create reviews for paid and delivered orders
        foreach ($paidOrders as $order) {
            // Get payment info
            $payment = $order->payments->first();
            
            // Only 70% of delivered orders get reviews (realistic scenario)
            if (rand(1, 100) > 70) {
                continue;
            }

            foreach ($order->orderItems as $orderItem) {
                // Only 80% of items in reviewed orders get individual reviews
                if (rand(1, 100) > 80) {
                    continue;
                }

                // Generate rating with bias towards higher ratings for paid orders
                $ratingWeights = $this->getProductRatingWeights($orderItem->masterItem->name_item, true);
                $rating = $this->weightedRandom($ratingWeights);

                // Get random comment for this rating
                $comments = $reviewTemplates[$rating];
                $comment = $this->customizeCommentForProduct($comments[array_rand($comments)], $orderItem->masterItem->name_item);
                
                // Add payment-related context to review
                $comment = $this->addPaymentContextToReview($comment, $payment->payment_type, $rating);

                // Review date should be after payment settlement and delivery
                $paymentDate = Carbon::parse($payment->updated_at);
                $reviewDate = $paymentDate->copy()->addDays(rand(1, 14)); // 1-14 days after payment

                // Create review
                Review::create([
                    'order_item_id' => $orderItem->id,
                    'customer_id' => $order->user_id,
                    'user_id' => $order->user_id,
                    'transaction_sales_id' => null,
                    'rating' => $rating,
                    'comment' => $comment,
                    'is_verified' => true, // Verified since payment is confirmed
                    'is_featured' => $rating >= 5 && rand(1, 100) <= 20, // 20% chance for 5-star reviews to be featured
                    'created_at' => $reviewDate,
                    'updated_at' => $reviewDate,
                ]);

                $reviewCount++;
            }
        }

        // Create additional reviews for products that don't have enough reviews
        // But only for products that have been in paid orders
        $reviewedProductIds = Review::join('order_items', 'reviews.order_item_id', '=', 'order_items.id')
            ->pluck('order_items.master_item_id')
            ->unique();
            
        $paidProductIds = OrderItem::whereHas('order', function($query) {
                $query->whereHas('payments', function($subQuery) {
                    $subQuery->where('transaction_status', 'settlement');
                })->where('status', 'delivered');
            })
            ->pluck('master_item_id')
            ->unique();
            
        foreach ($allProducts->whereIn('item_id', $paidProductIds) as $product) {
            // Check how many reviews this product already has
            $existingReviews = Review::whereHas('orderItem', function($query) use ($product) {
                $query->where('master_item_id', $product->item_id);
            })->count();
            
            // If product has less than 2 reviews, add more (but only from existing paid orders)
            if ($existingReviews < 2) {
                $productOrderItems = OrderItem::whereHas('order', function($query) {
                        $query->whereHas('payments', function($subQuery) {
                            $subQuery->where('transaction_status', 'settlement');
                        })->where('status', 'delivered');
                    })
                    ->where('master_item_id', $product->item_id)
                    ->whereDoesntHave('reviews') // Don't create duplicate reviews
                    ->with(['order.payments'])
                    ->get();
                    
                foreach ($productOrderItems->take(2) as $orderItem) {
                    $order = $orderItem->order;
                    $payment = $order->payments->first();
                    
                    if (!$payment) continue;
                    
                    // Generate rating with product-specific tendencies for paid orders
                    $ratingWeights = $this->getProductRatingWeights($product->name_item, true);
                    $rating = $this->weightedRandom($ratingWeights);
                    $comments = $reviewTemplates[$rating];
                    $comment = $this->customizeCommentForProduct($comments[array_rand($comments)], $product->name_item);
                    $comment = $this->addPaymentContextToReview($comment, $payment->payment_type, $rating);
                    
                    $paymentDate = Carbon::parse($payment->updated_at);
                    $reviewDate = $paymentDate->copy()->addDays(rand(1, 21));
                    
                    Review::create([
                        'order_item_id' => $orderItem->id,
                        'customer_id' => $order->user_id,
                        'user_id' => $order->user_id,
                        'transaction_sales_id' => null,
                        'rating' => $rating,
                        'comment' => $comment,
                        'is_verified' => true,
                        'is_featured' => $rating >= 5 && rand(1, 100) <= 15,
                        'created_at' => $reviewDate,
                        'updated_at' => $reviewDate,
                    ]);

                    $reviewCount++;
                }
            }
        }

        $this->command->info("Created {$reviewCount} product reviews successfully!");
        
        // Show detailed review statistics
        $avgRating = Review::avg('rating');
        $totalReviews = Review::count();
        
        $ratingDistribution = Review::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        $this->command->info("=== REVIEW STATISTICS ===");
        $this->command->info("Total Reviews Created: {$totalReviews}");
        $this->command->info("Average Rating: " . round($avgRating, 2) . "/5.0");
        $this->command->info("");
        $this->command->info("Rating Distribution:");
        foreach ($ratingDistribution as $dist) {
            $percentage = round(($dist->count / $totalReviews) * 100, 1);
            $stars = str_repeat('⭐', $dist->rating);
            $this->command->info("  {$stars} ({$dist->rating}): {$dist->count} reviews ({$percentage}%)");
        }
        
        // Show reviews per product
        $this->command->info("");
        $this->command->info("=== REVIEWS PER PRODUCT ===");
        $productStats = Review::join('order_items', 'reviews.order_item_id', '=', 'order_items.id')
            ->join('master_items', 'order_items.master_item_id', '=', 'master_items.item_id')
            ->selectRaw('master_items.name_item, COUNT(*) as review_count, AVG(reviews.rating) as avg_rating')
            ->groupBy('master_items.item_id', 'master_items.name_item')
            ->orderBy('review_count', 'desc')
            ->get();
            
        foreach ($productStats as $stat) {
            $avgRatingFormatted = number_format($stat->avg_rating, 1);
            $this->command->info("  {$stat->name_item}: {$stat->review_count} reviews (avg: {$avgRatingFormatted}/5.0)");
        }
    }

    /**
     * Generate weighted random number
     */
    private function weightedRandom(array $weights): int
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($weights as $value => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $value;
            }
        }
        
        return array_key_first($weights); // fallback
    }

    /**
     * Get product-specific rating weights
     */
    private function getProductRatingWeights(string $productName, bool $isPaidOrder = false): array
    {
        // Default weights favor higher ratings
        $defaultWeights = [1 => 5, 2 => 10, 3 => 15, 4 => 30, 5 => 40];
        
        // Adjust weights for paid orders (tend to be more positive)
        if ($isPaidOrder) {
            $defaultWeights = [1 => 3, 2 => 7, 3 => 12, 4 => 38, 5 => 40];
        }
        
        // Adjust weights based on product type
        if (str_contains(strtolower($productName), 'gentle baby')) {
            // Baby products tend to have very high ratings due to safety concerns
            return $isPaidOrder ? 
                [1 => 1, 2 => 3, 3 => 6, 4 => 30, 5 => 60] :
                [1 => 2, 2 => 5, 3 => 8, 4 => 25, 5 => 60];
        } elseif (str_contains(strtolower($productName), 'nyam')) {
            // Food products have good ratings but some variation
            return $isPaidOrder ?
                [1 => 2, 2 => 6, 3 => 10, 4 => 37, 5 => 45] :
                [1 => 3, 2 => 8, 3 => 12, 4 => 35, 5 => 42];
        } elseif (str_contains(strtolower($productName), 'healo')) {
            // Medical/health products have mixed reviews
            return $isPaidOrder ?
                [1 => 6, 2 => 10, 3 => 18, 4 => 33, 5 => 33] :
                [1 => 8, 2 => 12, 3 => 20, 4 => 30, 5 => 30];
        } elseif (str_contains(strtolower($productName), 'mamina')) {
            // Breast milk booster has varying effectiveness
            return $isPaidOrder ?
                [1 => 8, 2 => 13, 3 => 23, 4 => 32, 5 => 24] :
                [1 => 10, 2 => 15, 3 => 25, 4 => 30, 5 => 20];
        }
        
        return $defaultWeights;
    }

    /**
     * Customize comment based on product type
     */
    private function customizeCommentForProduct(string $comment, string $productName): string
    {
        // Add product-specific touches to comments
        if (str_contains(strtolower($productName), 'gentle baby')) {
            $babyTerms = ['untuk bayi', 'aman untuk anak', 'baby-friendly', 'cocok untuk balita'];
            $term = $babyTerms[array_rand($babyTerms)];
            return $comment . " Produk " . $term . "!";
        } elseif (str_contains(strtolower($productName), 'nyam')) {
            $foodTerms = ['enak', 'bergizi', 'sehat untuk anak', 'disukai anak'];
            $term = $foodTerms[array_rand($foodTerms)];
            return $comment . " Rasa " . $term . "!";
        } elseif (str_contains(strtolower($productName), 'healo')) {
            $medTerms = ['cepat sembuh', 'efektif', 'manjur', 'membantu penyembuhan'];
            $term = $medTerms[array_rand($medTerms)];
            return $comment . " Produk " . $term . "!";
        } elseif (str_contains(strtolower($productName), 'mamina')) {
            $mamiTerms = ['membantu ASI', 'melancarkan produksi', 'bagus untuk busui', 'recommended untuk ibu menyusui'];
            $term = $mamiTerms[array_rand($mamiTerms)];
            return $comment . " " . ucfirst($term) . "!";
        }
        
        return $comment;
    }
    
    /**
     * Add payment context to review comment
     */
    private function addPaymentContextToReview(string $comment, string $paymentType, int $rating): string
    {
        // Add payment-specific context based on payment type and rating
        $paymentContext = [];
        
        if ($paymentType === 'qris') {
            $paymentContext = [
                'Pembayaran QRIS sangat mudah dan cepat!',
                'Bayar pakai QRIS praktis banget.',
                'Scan QRIS langsung berhasil.',
                'Payment via QRIS lancar jaya!',
            ];
        } elseif (str_contains($paymentType, 'bank_transfer')) {
            $paymentContext = [
                'Transfer bank mudah dan aman.',
                'Proses transfer lancar.',
                'Pembayaran via transfer bank OK.',
                'Transfer mudah, barang cepat sampai.',
            ];
        } elseif (str_contains($paymentType, 'gopay')) {
            $paymentContext = [
                'Bayar pakai GoPay praktis.',
                'GoPay selalu jadi andalan.',
                'Pembayaran GoPay instant!',
                'Top up GoPay worth it untuk belanja.',
            ];
        } elseif (str_contains($paymentType, 'shopeepay')) {
            $paymentContext = [
                'ShopeePay memudahkan pembayaran.',
                'Bayar via ShopeePay lancar.',
                'ShopeePay praktis untuk checkout.',
                'Promo ShopeePay bikin hemat.',
            ];
        }
        
        // Only add payment context for good ratings (4-5 stars)
        if ($rating >= 4 && !empty($paymentContext) && rand(1, 100) <= 60) {
            $selectedContext = $paymentContext[array_rand($paymentContext)];
            return $comment . " " . $selectedContext;
        }
        
        return $comment;
    }
}