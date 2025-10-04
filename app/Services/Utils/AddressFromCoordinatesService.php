<?php

namespace App\Services\Utils;

use Illuminate\Support\Facades\Http;

class AddressFromCoordinatesService
{
    private const NOMINATIM_BASE_URL = 'https://nominatim.openstreetmap.org/reverse';

    private const PHOTON_BASE_URL = 'https://photon.komoot.io/reverse';

    private const PHOTON_DONSHONG_BASE_URL = 'https://photon.donsomhong.net/reverse';

    private const PHOTON_MARSMATHIS_BASE_URL = 'https://photon.marsmathis.com/reverse';

    private const PHOTON_KLLSWITCH_BASE_URL = 'https://photon.kllswitch.com/reverse';

    private const GEOCODE_XYZ_BASE_URL = 'https://geocode.xyz';

    private const BIGDATACLOUD_BASE_URL = 'https://api.bigdatacloud.net/data/reverse-geocode-client';

    public function getAddress(float $lat, float $lon, array $options = []): ?string
    {
        $methods = [
            'getAddressFromNominatim',
            'getAddressFromPhoton',
            'getAddressFromPhotonDonsomhong',
            'getAddressFromPhotonMarsmathis',
            'getAddressFromPhotonKllswitch',
            'getAddressFromGeocodeXYZ',
            'getAddressFromBigdatacloud',
        ];

        $merged = [];
        foreach ($methods as $method) {
            $result = $this->$method($lat, $lon, $options);
            if ($result) {
                foreach ($result as $key => $value) {
                    if ($value && ! isset($merged[$key])) {
                        $merged[$key] = $value;
                    }
                }
            }
        }

        if (empty($merged)) {
            return null;
        }
        $order = ['full', 'name', 'street', 'city', 'district', 'county', 'locality', 'region', 'principal_subdivision', 'state', 'country', 'postcode', 'postal'];
        $addressParts = [];
        foreach ($order as $key) {
            if (isset($merged[$key])) {
                $addressParts[] = $merged[$key];
            }
        }

        return implode(', ', array_unique($addressParts));
    }

    private function getAddressFromNominatim(float $lat, float $lon, array $options = []): ?array
    {
        $params = array_merge([
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lon,
        ], $options);

        try {
            $response = Http::get(self::NOMINATIM_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();

                return ['full' => $data['display_name'] ?? null];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromPhoton(float $lat, float $lon, array $options = []): ?string
    {
        $params = array_merge([
            'lat' => $lat,
            'lon' => $lon,
        ], $options);

        try {
            $response = Http::get(self::PHOTON_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (! empty($data['features'])) {
                    $properties = $data['features'][0]['properties'];
                    $keys = ['name', 'street', 'city', 'district', 'state', 'country', 'postcode'];
                    $addressParts = array_filter(array_map(fn ($key) => $properties[$key] ?? null, $keys), fn ($val) => ! empty($val));

                    return implode(', ', $addressParts);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromPhotonDonsomhong(float $lat, float $lon, array $options = []): ?string
    {
        $params = array_merge([
            'lat' => $lat,
            'lon' => $lon,
        ], $options);

        try {
            $response = Http::get(self::PHOTON_DONSHONG_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (! empty($data['features'])) {
                    $properties = $data['features'][0]['properties'];
                    $keys = ['name', 'street', 'city', 'district', 'county', 'state', 'country', 'postcode'];
                    $addressParts = array_filter(array_map(fn ($key) => $properties[$key] ?? null, $keys), fn ($val) => ! empty($val));

                    return implode(', ', $addressParts);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromPhotonMarsmathis(float $lat, float $lon, array $options = []): ?string
    {
        $params = array_merge([
            'lat' => $lat,
            'lon' => $lon,
        ], $options);

        try {
            $response = Http::get(self::PHOTON_MARSMATHIS_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (! empty($data['features'])) {
                    $properties = $data['features'][0]['properties'];
                    $keys = ['name', 'street', 'city', 'district', 'county', 'state', 'country', 'postcode'];
                    $addressParts = array_filter(array_map(fn ($key) => $properties[$key] ?? null, $keys), fn ($val) => ! empty($val));

                    return implode(', ', $addressParts);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromPhotonKllswitch(float $lat, float $lon, array $options = []): ?string
    {
        $params = array_merge([
            'lat' => $lat,
            'lon' => $lon,
        ], $options);

        try {
            $response = Http::get(self::PHOTON_KLLSWITCH_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (! empty($data['features'])) {
                    $properties = $data['features'][0]['properties'];
                    $keys = ['name', 'street', 'city', 'district', 'county', 'state', 'country', 'postcode'];
                    $addressParts = array_filter(array_map(fn ($key) => $properties[$key] ?? null, $keys), fn ($val) => ! empty($val));

                    return implode(', ', $addressParts);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromGeocodeXYZ(float $lat, float $lon, array $options = []): ?string
    {
        $url = self::GEOCODE_XYZ_BASE_URL."/{$lat},{$lon}?geoit=json";

        try {
            $response = Http::get($url, $options);

            if ($response->successful()) {
                $data = $response->json();
                $addressParts = array_filter([
                    $data['staddress'] ?? null,
                    $data['city'] ?? null,
                    $data['region'] ?? null,
                    $data['postal'] ?? null,
                    $data['countryname'] ?? null,
                ], fn ($val) => ! empty($val));

                return implode(', ', $addressParts);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getAddressFromBigdatacloud(float $lat, float $lon, array $options = []): ?string
    {
        $params = array_merge([
            'latitude' => $lat,
            'longitude' => $lon,
            'localityLanguage' => 'es',
        ], $options);

        try {
            $response = Http::get(self::BIGDATACLOUD_BASE_URL, $params);

            if ($response->successful()) {
                $data = $response->json();
                $addressParts = array_filter([
                    $data['locality'] ?? null,
                    $data['city'] ?? null,
                    $data['principalSubdivision'] ?? null,
                    $data['countryName'] ?? null,
                    $data['postcode'] ?? null,
                ], fn ($val) => ! empty($val));

                return implode(', ', $addressParts);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
