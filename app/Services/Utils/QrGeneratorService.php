<?php

namespace App\Services\Utils;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\EpsWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WebPWriter;
use InvalidArgumentException;

class QrGeneratorService
{
    public function generate(string $data, array $options = []): string
    {
        $cleanedData = $this->cleanData($data);
        $format = $options['format'] ?? 'png';
        $size = $options['size'] ?? 300;
        $margin = $options['margin'] ?? 10;
        $errorCorrectionLevel = $this->mapErrorCorrectionLevel($options['errorCorrectionLevel'] ?? 'high');
        $encoding = $options['encoding'] ?? 'UTF-8';

        $writer = $this->getWriter($format);

        $builder = new Builder(
            writer: $writer,
            data: $cleanedData,
            encoding: new Encoding($encoding),
            errorCorrectionLevel: $errorCorrectionLevel,
            size: $size,
            margin: $margin
        );

        $result = $builder->build();

        return $result->getDataUri();
    }

    private function mapErrorCorrectionLevel(string $level)
    {
        return match ($level) {
            'low' => ErrorCorrectionLevel::Low,
            'medium' => ErrorCorrectionLevel::Medium,
            'quartile' => ErrorCorrectionLevel::Quartile,
            'high' => ErrorCorrectionLevel::High,
            default => ErrorCorrectionLevel::High,
        };
    }

    private function getWriter(string $format)
    {
        return match ($format) {
            'png' => new PngWriter,
            'svg' => new SvgWriter,
            'eps' => new EpsWriter,
            'webp' => new WebPWriter,
            default => throw new InvalidArgumentException("Formato no soportado: {$format}. Usa 'png', 'svg', 'eps' o 'webp'."),
        };
    }

    public function cleanData(mixed $data): string
    {
        if (is_string($data) && ! str_starts_with(trim($data), '{') && ! str_starts_with(trim($data), '[')) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        $decoded = null;
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException('Datos JSON inválidos.');
            }
        } elseif (is_array($data)) {
            $decoded = $data;
        } elseif (is_object($data)) {
            $decoded = (array) $data;
        } else {
            throw new InvalidArgumentException('Tipo de datos no soportado para limpiar.');
        }
        $flatten = function ($value, $prefix = '') use (&$flatten) {
            $result = [];
            if (is_array($value) || is_object($value)) {
                foreach ((array) $value as $key => $val) {
                    $newPrefix = $prefix ? "{$prefix}.{$key}" : $key;
                    $result = array_merge($result, $flatten($val, $newPrefix));
                }
            } else {
                $result[] = "{$prefix}: ".htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            }

            return $result;
        };

        $flattened = $flatten($decoded);

        return implode("\n", $flattened);
    }
}
