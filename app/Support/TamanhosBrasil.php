<?php

namespace App\Support;

/**
 * Grades usuais no Brasil para roupa, calçado e volume (ml/litros).
 */
final class TamanhosBrasil
{
    public const TIPO_UNICO = 'unico';

    public const TIPO_ROUPA = 'roupa';

    public const TIPO_CALCADO = 'calcado';

    public const TIPO_VOLUME = 'volume';

    /**
     * @return array<string, string>
     */
    public static function labelsTipo(): array
    {
        return [
            self::TIPO_UNICO => 'Sem grade (produto único)',
            self::TIPO_ROUPA => 'Roupas (P, M, G, GG…)',
            self::TIPO_CALCADO => 'Calçados (numeração BR)',
            self::TIPO_VOLUME => 'Perfumes/volumes (ml e litros)',
        ];
    }

    /**
     * @return list<string>
     */
    public static function opcoesRoupa(): array
    {
        return [
            'PP', 'P', 'M', 'G', 'GG', 'GGG', 'XG', 'XXG', 'EG',
        ];
    }

    /**
     * Numeração BR comum (33 a 48).
     *
     * @return list<string>
     */
    public static function opcoesCalcado(): array
    {
        $out = [];
        for ($n = 33; $n <= 48; $n++) {
            $out[] = (string) $n;
        }

        return $out;
    }

    /**
     * Volumes comuns para perfumes e líquidos.
     *
     * @return list<string>
     */
    public static function opcoesVolume(): array
    {
        return [
            '5ml', '10ml', '15ml', '20ml', '30ml', '50ml', '60ml',
            '75ml', '80ml', '90ml', '100ml', '120ml', '150ml', '200ml',
            '250ml', '300ml', '500ml', '750ml',
            '1L', '1.5L', '2L', '3L', '5L',
        ];
    }

    /**
     * @return list<string>
     */
    public static function opcoesParaTipo(string $tipo): array
    {
        return match ($tipo) {
            self::TIPO_ROUPA => self::opcoesRoupa(),
            self::TIPO_CALCADO => self::opcoesCalcado(),
            self::TIPO_VOLUME => self::opcoesVolume(),
            default => [],
        };
    }
}
