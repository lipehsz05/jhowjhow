<?php

namespace App\Support;

/**
 * Grades de tamanho usuais no Brasil para categorias de roupa e calçado.
 */
final class TamanhosBrasil
{
    public const TIPO_UNICO = 'unico';

    public const TIPO_ROUPA = 'roupa';

    public const TIPO_CALCADO = 'calcado';

    /**
     * @return array<string, string>
     */
    public static function labelsTipo(): array
    {
        return [
            self::TIPO_UNICO => 'Sem grade (produto único)',
            self::TIPO_ROUPA => 'Roupas (P, M, G, GG…)',
            self::TIPO_CALCADO => 'Calçados (numeração BR)',
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
     * @return list<string>
     */
    public static function opcoesParaTipo(string $tipo): array
    {
        return match ($tipo) {
            self::TIPO_ROUPA => self::opcoesRoupa(),
            self::TIPO_CALCADO => self::opcoesCalcado(),
            default => [],
        };
    }
}
