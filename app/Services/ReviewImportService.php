<?php

namespace App\Services;

use App\Models\{
    Review,
    SchoolDetail
};
use Illuminate\Support\Facades\DB;

class ReviewImportService
{
    /**
     * Memproses data array dan menyimpannya ke database
     */
    public function import(array $data)
    {
        DB::beginTransaction();

        try {
            foreach ($data as $item) {
                // 1. Validasi: Pastikan data penting ada
                if (empty($item['npsn']) || empty($item['review_rating'])) {
                    continue; // Skip jika data tidak lengkap
                }

                // 2. Cari Sekolah berdasarkan NPSN (institutionCode)
                // Sesuaikan 'institutionCode' dengan nama kolom NPSN di tabel school_details kamu
                $schoolDetail = SchoolDetail::where('institutionCode', $item['npsn'])->first();

                // Jika sekolah ditemukan, simpan reviewnya
                if ($schoolDetail) {
                    Review::UpdateOrCreate([
                        'schoolDetailId' => $schoolDetail->id,
                        'userId'         => null, // Null karena dari Google
                        'reviewer_name'  => $item['reviewer_name'] ?? 'Google User',
                        'reviewText'     => $item['review_text'] ?? null,
                        'rating'         => $item['review_rating'],
                        'source'         => 'google', // Menandai ini review dari Google
                        // created_at akan otomatis terisi now(), atau bisa ambil dari CSV jika ada kolom tanggal
                        'status' => Review::STATUS_APPROVED,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Membaca file CSV dan mengubahnya menjadi Array (Preview)
     * Sama persis dengan logika CSVImportService kamu
     */
    public function preview($file): array
    {
        $csvData = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Ambil header
            $header = fgetcsv($handle, 1000, ',');

            // Loop baris data
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Pastikan jumlah kolom sama dengan header untuk menghindari error array_combine
                if (count($header) === count($row)) {
                    $csvData[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $csvData;
    }
}
