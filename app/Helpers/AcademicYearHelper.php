<?php

namespace App\Helpers;

use Carbon\Carbon;

class AcademicYearHelper
{
    /**
     * Mendapatkan tahun ajaran saat ini berdasarkan bulan.
     * Jika bulan Juli (7) atau setelahnya, tahun ajaran = "TahunIni/TahunDepan".
     * Jika sebelum bulan Juli, tahun ajaran = "TahunLalu/TahunIni".
     *
     * @return string
     */
    public static function getCurrentAcademicYear(): string
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        if ($currentMonth >= 7) {
            return $currentYear . '/' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '/' . $currentYear;
        }
    }

    /**
     * Mendapatkan tanggal mulai dan selesai dari sebuah tahun ajaran.
     * Format tahun ajaran yang diharapkan: "YYYY/YYYY" (misal: "2024/2025").
     * Jika null, akan menggunakan tahun ajaran saat ini.
     *
     * @param string|null $academicYear
     * @return array
     */
    public static function getAcademicYearDateRange(?string $academicYear = null): array
    {
        if (!$academicYear) {
            $academicYear = self::getCurrentAcademicYear();
        }

        $years = explode('/', $academicYear);
        if (count($years) !== 2) {
            throw new \InvalidArgumentException("Format tahun ajaran tidak valid. Gunakan format YYYY/YYYY");
        }

        $startYear = trim($years[0]);
        $endYear = trim($years[1]);

        return [
            'start' => Carbon::createFromDate($startYear, 7, 1)->startOfDay()->toDateTimeString(),
            'end'   => Carbon::createFromDate($endYear, 6, 30)->endOfDay()->toDateTimeString(),
        ];
    }

    /**
     * Mendapatkan predikat siswa berdasarkan total poin.
     *
     * @param int $totalPoin
     * @return array ['label' => string, 'color' => string]
     */
    public static function getPredikat(int $totalPoin): array
    {
        if ($totalPoin == 0) {
            return ['label' => 'Siswa Teladan', 'color' => 'green'];
        } elseif ($totalPoin >= 1 && $totalPoin <= 20) {
            return ['label' => 'Siswa Baik', 'color' => 'blue'];
        } elseif ($totalPoin >= 21 && $totalPoin <= 40) {
            return ['label' => 'Perlu Perhatian', 'color' => 'yellow']; // Range SP1
        } elseif ($totalPoin >= 41 && $totalPoin <= 60) {
            return ['label' => 'Dalam Pembinaan', 'color' => 'orange']; // Range SP2
        } else {
            return ['label' => 'Kasus Berat', 'color' => 'red']; // Range SP3 (> 60)
        }
    }
}
