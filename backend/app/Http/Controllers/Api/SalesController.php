<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Models\SalesCountry;
use App\Models\SalesRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $query = Sales::with(['countries.regions']);

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $sales = $query->latest()->get();

        if ($sales->isEmpty() && !empty($search)) {
            $fetchedSales = $this->fetchSales($search);
            if ($fetchedSales) {
                $sales = collect([$fetchedSales]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data sales berhasil diambil',
            'data' => $sales,
        ]);
    }

    private function fetchSales($name)
    {
        $existingSales = Sales::with(['countries.regions'])->where('name', $name)->first();
        if ($existingSales) {
            return $existingSales;
        }

        DB::beginTransaction();
        try {
            $extUrl = 'https://api.genderize.io/?name=' . urlencode($name);
            $extUrlCountry = 'https://api.nationalize.io/?name=' . urlencode($name);
            $extUrlRegion = 'https://api.first.org/data/v1/countries?limit=300';

            $res = Http::timeout(10)->get($extUrl);
            $sales = null;

            if ($res->successful()) {
                $sales = $res->json();
            } else {
                Log::warning("Genderize API error: {$res->body()}");
                return null;
            }

            if (empty($sales) || empty($sales['name'])) {
                return null;
            }

            $sales = Sales::create([
                'name' => $sales['name'],
                'gender' => $sales['gender'] ?? 'unknown',
                'count' => $sales['count'] ?? 0,
                'probability' => $sales['probability'] ?? 0.00,
            ]);

            $countryRes = Http::timeout(10)->get($extUrlCountry);
            $countryList = [];

            if ($countryRes->successful()) {
                $countryData = $countryRes->json();
                $countryList = $countryData['country'] ?? [];
            } else {
                Log::warning("Nationalize API error: {$countryRes->body()}");
            }

            if (!empty($countryList)) {
                $regionRes = Http::timeout(15)->get($extUrlRegion);
                $regionsData = [];
                if ($regionRes->successful()) {
                    $regionsData = $regionRes->json('data') ?? [];
                } else {
                    Log::warning("Region API error: {$regionRes->body()}");
                }

                foreach ($countryList as $item) {
                    $countryCode = $item['country_id'] ?? null;
                    if (!$countryCode) {
                        continue;
                    }

                    $salesCountry = SalesCountry::create([
                        'sales_id' => $sales->id,
                        'country_id' => $countryCode,
                        'probability' => $item['probability'] ?? 0.00,
                    ]);

                    $regionName = $regionsData[$countryCode]['region'] ?? 'Unknown';
                    SalesRegion::create([
                        'country_id' => $salesCountry->id,
                        'country_code' => $countryCode,
                        'region' => $regionName,
                    ]);
                }
            }

            DB::commit();

            return $sales->load(['countries.regions']);
        } catch (Throwable $ex) {
            DB::rollBack();
            Log::error('Fetch sales external API error: ' . $ex->getMessage());
            return null;
        }
    }
}
