<?php

namespace App\Support;

/**
 * Formatação e normalização (apenas dígitos no banco) para telefone, CPF/CNPJ e CEP.
 */
class BrFormat
{
    public static function onlyDigits(?string $value): string
    {
        return preg_replace('/\D/', '', (string) ($value ?? ''));
    }

    public static function normalizeTelefone(?string $value): ?string
    {
        $d = self::onlyDigits($value);
        if ($d === '') {
            return null;
        }
        if (str_starts_with($d, '55') && strlen($d) > 11) {
            $d = substr($d, 2);
        }
        if (strlen($d) > 11) {
            $d = substr($d, 0, 11);
        }

        return $d;
    }

    public static function normalizeCpfCnpj(?string $value): ?string
    {
        $d = self::onlyDigits($value);
        if ($d === '') {
            return null;
        }
        if (strlen($d) > 14) {
            $d = substr($d, 0, 14);
        }

        return $d;
    }

    public static function normalizeCep(?string $value): ?string
    {
        $d = self::onlyDigits($value);
        if ($d === '') {
            return null;
        }
        if (strlen($d) > 8) {
            $d = substr($d, 0, 8);
        }

        return $d;
    }

    public static function telefoneDisplay(?string $digits): string
    {
        $d = self::onlyDigits($digits);
        if ($d === '') {
            return '';
        }
        if (strlen($d) > 11) {
            $d = substr($d, 0, 11);
        }
        $ddd = substr($d, 0, 2);
        $rest = substr($d, 2);
        if ($rest === '' || $rest === false) {
            return '('.$ddd;
        }
        if (strlen($rest) <= 4) {
            return '('.$ddd.') '.$rest;
        }
        if (strlen($rest) === 8) {
            return sprintf('(%s) %s-%s', $ddd, substr($rest, 0, 4), substr($rest, 4, 4));
        }

        return sprintf('(%s) %s-%s', $ddd, substr($rest, 0, 5), substr($rest, 5, 4));
    }

    public static function cpfCnpjDisplay(?string $digits): string
    {
        $d = self::onlyDigits($digits);
        if ($d === '') {
            return '';
        }
        if (strlen($d) <= 11) {
            if (strlen($d) <= 3) {
                return $d;
            }
            if (strlen($d) <= 6) {
                return substr($d, 0, 3).'.'.substr($d, 3);
            }
            if (strlen($d) <= 9) {
                return substr($d, 0, 3).'.'.substr($d, 3, 3).'.'.substr($d, 6);
            }

            return substr($d, 0, 3).'.'.substr($d, 3, 3).'.'.substr($d, 6, 3).'-'.substr($d, 9, 2);
        }
        $d = substr($d, 0, 14);
        if (strlen($d) <= 2) {
            return $d;
        }
        if (strlen($d) <= 5) {
            return substr($d, 0, 2).'.'.substr($d, 2);
        }
        if (strlen($d) <= 8) {
            return substr($d, 0, 2).'.'.substr($d, 2, 3).'.'.substr($d, 5);
        }
        if (strlen($d) <= 12) {
            return substr($d, 0, 2).'.'.substr($d, 2, 3).'.'.substr($d, 5, 3).'/'.substr($d, 8);
        }

        return substr($d, 0, 2).'.'.substr($d, 2, 3).'.'.substr($d, 5, 3).'/'.substr($d, 8, 4).'-'.substr($d, 12, 2);
    }

    public static function cepDisplay(?string $digits): string
    {
        $d = self::onlyDigits($digits);
        if ($d === '') {
            return '';
        }
        $d = substr($d, 0, 8);
        if (strlen($d) <= 5) {
            return $d;
        }

        return substr($d, 0, 5).'-'.substr($d, 5);
    }
}
