<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use App\Models\TransactionSales;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    private $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces(): JsonResponse
    {
        $provinces = $this->rajaOngkirService->getProvinces();
        
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    public function getCities(Request $request): JsonResponse
    {
        $provinceId = $request->get('province_id');
        $cities = $this->rajaOngkirService->getCities($provinceId);
        
        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    public function calculateShippingCost(Request $request): JsonResponse
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string|in:jne,pos,tiki'
        ]);

        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $weight = $request->input('weight');
        $courier = $request->input('courier');

        $results = $this->rajaOngkirService->calculateShippingCost(
            $origin,
            $destination,
            $weight,
            $courier
        );

        if (empty($results)) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível calcular o custo de envio'
            ], 400);
        }

        $formattedCosts = $this->rajaOngkirService->formatShippingCosts($results);

        return response()->json([
            'success' => true,
            'data' => $formattedCosts
        ]);
    }

    public function calculateAllShippingCosts(Request $request): JsonResponse
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1'
        ]);

        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $weight = $request->input('weight');
        $couriers = ['jne', 'pos', 'tiki'];

        $allCosts = [];

        foreach ($couriers as $courier) {
            $results = $this->rajaOngkirService->calculateShippingCost(
                $origin,
                $destination,
                $weight,
                $courier
            );

            if (!empty($results)) {
                $formattedCosts = $this->rajaOngkirService->formatShippingCosts($results);
                $allCosts = array_merge($allCosts, $formattedCosts);
            }
        }

        usort($allCosts, function($a, $b) {
            return $a['cost'] <=> $b['cost'];
        });

        return response()->json([
            'success' => true,
            'data' => $allCosts
        ]);
    }

    public function trackDelivery(Request $request): JsonResponse
    {
        $request->validate([
            'waybill' => 'required|string',
            'courier' => 'required|string|in:jne,pos,tiki'
        ]);

        $waybill = $request->input('waybill');
        $courier = $request->input('courier');

        $result = $this->rajaOngkirService->trackDelivery($waybill, $courier);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível rastrear a entrega'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getAvailableCouriers(): JsonResponse
    {
        $couriers = $this->rajaOngkirService->getAvailableCouriers();
        
        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    }

    public function updateTrackingNumber(Request $request): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'required|integer|exists:transaction_sales,transaction_sales_id',
            'tracking_number' => 'required|string'
        ]);

        $transaction = TransactionSales::find($request->input('transaction_id'));
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaction->update([
            'tracking_number' => $request->input('tracking_number')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor tracking berhasil diperbarui',
            'data' => $transaction
        ]);
    }

    public function getTransactionTracking(Request $request): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'required|integer|exists:transaction_sales,transaction_sales_id'
        ]);

        $transaction = TransactionSales::find($request->input('transaction_id'));
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if (!$transaction->tracking_number || !$transaction->shipping_courier) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor tracking atau kurir belum tersedia'
            ], 400);
        }

        $trackingResult = $this->rajaOngkirService->trackDelivery(
            $transaction->tracking_number,
            $transaction->shipping_courier
        );

        if (!$trackingResult) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat melacak pengiriman'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transaction' => $transaction,
                'tracking' => $trackingResult
            ]
        ]);
    }
}
