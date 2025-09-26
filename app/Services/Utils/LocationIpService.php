<?php

namespace App\Services\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationIpService
{
    private const IPINFO_URL = 'https://ipinfo.io/json';

    private const IPAPI_URL = 'https://ipapi.co/json/';

    private const FREEIP_URL = 'https://free.freeipapi.com/api/json/';

    private const IPBASE_URL = 'https://api.ipbase.com/v1/json/';

    private const GEOLOCATION_URL = 'https://geolocation-db.com/json/';

    private const BIGDATA_URL = 'https://api.bigdatacloud.net/data/reverse-geocode-client';

    private const IPWHOIS_URL = 'https://ipwho.is/';

    /**
     * Get location coordinates from multiple providers
     */
    public function getCoordinates(): ?array
    {
        $location =
            $this->getLocationFromIpInfo() ??
            $this->getLocationFromIpApi() ??
            $this->getLocationFromFreeIp() ??
            $this->getLocationFromIpBase() ??
            $this->getLocationFromGeolocationDb() ??
            $this->getLocationFromBigData() ??
            $this->getLocationFromIpWhois();

        if (! $this->hasValidCoordinates($location)) {
            return null;
        }

        return [
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'city' => $location['city'] ?? null,
        ];
    }

    /**
     * Get full location data from ipinfo.io
     */
    private function getLocationFromIpInfo(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::IPINFO_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['loc'])) {
                    return null;
                }

                $coordinates = explode(',', $data['loc']);

                if (count($coordinates) !== 2) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip' => $data['ip'] ?? null,
                    'hostname' => $data['hostname'] ?? null,
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null,
                    'country' => $data['country'] ?? null,
                    'latitude' => (float) $coordinates[0],
                    'longitude' => (float) $coordinates[1],
                    'postal_code' => $data['postal'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'organization' => $data['org'] ?? null,
                    'provider' => 'ipinfo',
                ];
            }
        } catch (\Exception $e) {
            Log::error('IpInfo Error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get full location data from ipapi.co
     */
    private function getLocationFromIpApi(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::IPAPI_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['latitude'], $data['longitude'])) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip' => $data['ip'] ?? null,
                    'network' => $data['network'] ?? null,
                    'version' => $data['version'] ?? null,
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null,
                    'region_code' => $data['region_code'] ?? null,
                    'country_name' => $data['country_name'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                    'country_capital' => $data['country_capital'] ?? null,
                    'continent_code' => $data['continent_code'] ?? null,
                    'latitude' => (float) $data['latitude'] ?? null,
                    'longitude' => (float) $data['longitude'] ?? null,
                    'postal' => $data['postal'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'utc_offset' => $data['utc_offset'] ?? null,
                    'currency' => $data['currency'] ?? null,
                    'currency_name' => $data['currency_name'] ?? null,
                    'languages' => $data['languages'] ?? null,
                    'asn' => $data['asn'] ?? null,
                    'org' => $data['org'] ?? null,
                    'provider' => 'ipapi',
                ];
            }
        } catch (\Exception $e) {
            Log::error('IpApi Error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get full location data from freeipapi.com
     */
    private function getLocationFromFreeIp(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::FREEIP_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['latitude'], $data['longitude'])) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip_version' => $data['ipVersion'] ?? null,
                    'ip_address' => $data['ipAddress'] ?? null,
                    'latitude' => (float) $data['latitude'] ?? null,
                    'longitude' => (float) $data['longitude'] ?? null,
                    'country_name' => $data['countryName'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'capital' => $data['capital'] ?? null,
                    'phone_codes' => $data['phoneCodes'] ?? [],
                    'time_zones' => $data['timeZones'] ?? [],
                    'zip_code' => $data['zipCode'] ?? null,
                    'city_name' => $data['cityName'] ?? null,
                    'region_name' => $data['regionName'] ?? null,
                    'continent' => $data['continent'] ?? null,
                    'continent_code' => $data['continentCode'] ?? null,
                    'currencies' => $data['currencies'] ?? [],
                    'languages' => $data['languages'] ?? [],
                    'asn' => $data['asn'] ?? null,
                    'asn_organization' => $data['asnOrganization'] ?? null,
                    'is_proxy' => $data['isProxy'] ?? false,
                    'provider' => 'freeipapi',
                ];
            }
        } catch (\Exception $e) {
            Log::error('FreeIpApi Error: '.$e->getMessage());
        }

        return null;
    }

    private function getLocationFromIpBase(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::IPBASE_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['latitude'], $data['longitude'])) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip' => $data['ip'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                    'country_name' => $data['country_name'] ?? null,
                    'region_code' => $data['region_code'] ?? null,
                    'region_name' => $data['region_name'] ?? null,
                    'city' => $data['city'] ?? null,
                    'postal_code' => $data['zip_code'] ?? null,
                    'timezone' => $data['time_zone'] ?? null,
                    'latitude' => (float) $data['latitude'] ?? null,
                    'longitude' => (float) $data['longitude'] ?? null,
                    'metro_code' => (int) $data['metro_code'] ?? 0,
                    'provider' => 'ipbase',
                ];
            }
        } catch (\Exception $e) {
            Log::error('IpBase Error: '.$e->getMessage());
        }

        return null;
    }

    private function getLocationFromGeolocationDb(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::GEOLOCATION_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['latitude'], $data['longitude'])) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip' => $data['IPv4'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                    'country_name' => $data['country_name'] ?? null,
                    'region' => $data['state'] ?? null,
                    'city' => $data['city'] ?? null,
                    'postal_code' => $data['postal'] ?? null,
                    'latitude' => (float) $data['latitude'] ?? null,
                    'longitude' => (float) $data['longitude'] ?? null,
                    'provider' => 'geolocationdb',
                ];
            }
        } catch (\Exception $e) {
            Log::error('GeolocationDB Error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get full location data from bigdatacloud.net
     */
    private function getLocationFromBigData(): ?array
    {
        try {
            $response = Http::timeout(5)
                ->get(self::BIGDATA_URL, [
                    'localityLanguage' => 'es',
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'full_response' => $data,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'lookup_source' => $data['lookupSource'] ?? null,
                    'locality_language' => $data['localityLanguageRequested'] ?? null,
                    'continent' => $data['continent'] ?? null,
                    'continent_code' => $data['continentCode'] ?? null,
                    'country_name' => $data['countryName'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'principal_subdivision' => $data['principalSubdivision'] ?? null,
                    'principal_subdivision_code' => $data['principalSubdivisionCode'] ?? null,
                    'city' => $data['city'] ?? null,
                    'locality' => $data['locality'] ?? null,
                    'postal_code' => $data['postcode'] ?? null,
                    'plus_code' => $data['plusCode'] ?? null,
                    'locality_info' => [
                        'administrative' => $data['localityInfo']['administrative'] ?? [],
                        'informative' => $data['localityInfo']['informative'] ?? [],
                    ],
                    'provider' => 'bigdatacloud',
                ];
            }
        } catch (\Exception $e) {
            Log::error('BigDataCloud Error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Get full location data from ipwhois.is
     */
    private function getLocationFromIpWhois(): ?array
    {
        try {
            $response = Http::timeout(5)->get(self::IPWHOIS_URL);

            if ($response->successful()) {
                $data = $response->json();

                if (! $data['success'] || ! isset($data['latitude'], $data['longitude'])) {
                    return null;
                }

                return [
                    'full_response' => $data,
                    'ip' => $data['ip'] ?? null,
                    'type' => $data['type'] ?? null,
                    'continent' => $data['continent'] ?? null,
                    'continent_code' => $data['continent_code'] ?? null,
                    'country' => $data['country'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                    'region' => $data['region'] ?? null,
                    'region_code' => $data['region_code'] ?? null,
                    'city' => $data['city'] ?? null,
                    'latitude' => (float) $data['latitude'] ?? null,
                    'longitude' => (float) $data['longitude'] ?? null,
                    'postal_code' => $data['postal'] ?? null,
                    'calling_code' => $data['calling_code'] ?? null,
                    'capital' => $data['capital'] ?? null,
                    'borders' => $data['borders'] ?? null,
                    'flag' => $data['flag'] ?? null,
                    'connection' => $data['connection'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'provider' => 'ipwhois',
                ];
            }
        } catch (\Exception $e) {
            Log::error('IpWhois Error: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Validate coordinates
     */
    private function hasValidCoordinates(?array $location): bool
    {
        return $location &&
            isset($location['latitude'], $location['longitude']) &&
            is_numeric($location['latitude']) &&
            is_numeric($location['longitude']);
    }
}
