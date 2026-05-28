<?php

namespace App\Support;

/**
 * Konversi nilai numerik ke huruf (skala standar PLP di aplikasi).
 */
final class Grading
{
    public static function numericToLetter(?float $grade): ?string
    {
        if ($grade === null) {
            return null;
        }

        if ($grade >= 85) {
            return 'A';
        }
        if ($grade >= 77) {
            return 'A-';
        }
        if ($grade >= 69) {
            return 'B+';
        }
        if ($grade >= 61) {
            return 'B';
        }
        if ($grade >= 53) {
            return 'B-';
        }
        if ($grade >= 45) {
            return 'C+';
        }
        if ($grade >= 37) {
            return 'C';
        }
        if ($grade >= 29) {
            return 'C-';
        }
        if ($grade >= 21) {
            return 'D';
        }

        return 'E';
    }
}
