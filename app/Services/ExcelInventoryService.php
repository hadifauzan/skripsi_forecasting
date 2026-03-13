<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExcelInventoryService
{
	private static ?\PhpOffice\PhpSpreadsheet\Spreadsheet $cached = null;

	private string $filePath;

	/** Maps Indonesian month name prefix (lowercase) to month number */
	private array $monthMap = [
		'jan'       => 1,
		'feb'       => 2,
		'mar'       => 3,
		'apr'       => 4,
		'mei'       => 5,
		'juni'      => 6,
		'jul'       => 7,
		'agustus'   => 8,
		'september' => 9,
		'oktober'   => 10,
		'november'  => 11,
		'desember'  => 12,
	];

	public function __construct()
	{
		$this->filePath = storage_path('app/2025_update_stok.zip');
	}

	private function spreadsheet(): \PhpOffice\PhpSpreadsheet\Spreadsheet
	{
		if (self::$cached === null) {
			$reader = IOFactory::createReader('Xlsx');
			$reader->setReadDataOnly(true);
			self::$cached = $reader->load($this->filePath);
		}

		return self::$cached;
	}

	private function parseSheetMonth(string $name): ?int
	{
		$lower = strtolower(trim($name));

		foreach ($this->monthMap as $keyword => $num) {
			if (str_starts_with($lower, $keyword)) {
				return $num;
			}
		}

		return null;
	}

	/** Returns [month_number => sheet_name], sorted by month. */
	private function sheetsByMonth(): array
	{
		$result = [];

		foreach ($this->spreadsheet()->getSheetNames() as $name) {
			$m = $this->parseSheetMonth($name);
			if ($m !== null) {
				$result[$m] = $name;
			}
		}

		ksort($result);

		return $result;
	}

	private function getSheetByMonth(int $month): ?\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
	{
		$sheets = $this->sheetsByMonth();

		if (!isset($sheets[$month])) {
			return null;
		}

		return $this->spreadsheet()->getSheetByName($sheets[$month]);
	}

	private function getLatestSheet(): ?\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
	{
		$sheets = $this->sheetsByMonth();

		if (empty($sheets)) {
			return null;
		}

		return $this->spreadsheet()->getSheetByName($sheets[max(array_keys($sheets))]);
	}

	/**
	 * Compute totals by summing raw transaction quantity columns directly:
	 * C = stok masuk quantity, I = stok keluar quantity.
	 */
	private function sumRawTransactions(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
	{
		$masuk = 0.0;
		$keluar = 0.0;
		$highestRow = min((int) $sheet->getHighestRow(), 1001);

		for ($row = 3; $row <= $highestRow; $row++) {
			$c = $sheet->getCell("C{$row}")->getValue();
			$i = $sheet->getCell("I{$row}")->getValue();

			if (is_numeric($c)) {
				$masuk += (float) $c;
			}
			if (is_numeric($i)) {
				$keluar += (float) $i;
			}
		}

		return [
			'masuk' => (int) $masuk,
			'keluar' => (int) $keluar,
		];
	}

	private function excelDateToString(mixed $raw, string $format = 'd M Y'): ?string
	{
		if (!is_numeric($raw) || $raw <= 0) {
			return null;
		}

		try {
			return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $raw))->format($format);
		} catch (\Exception) {
			return null;
		}
	}

	/**
	 * Return the sheet for the current month (if data exists in Excel),
	 * otherwise fall back to the latest available month.
	 */
	public function getActiveSheet(): ?\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
	{
		$sheet = $this->getSheetByMonth(now()->month);

		return $sheet ?? $this->getLatestSheet();
	}

	/** Human-readable name of the active month sheet. */
	public function getActiveMonthName(): string
	{
		$sheets = $this->sheetsByMonth();

		if (empty($sheets)) {
			return '-';
		}

		$month = now()->month;

		return $sheets[$month] ?? $sheets[max(array_keys($sheets))];
	}

	/** Total Stok Masuk and Stok Keluar for the active month. */
	public function getActiveSummary(): array
	{
		$sheet = $this->getActiveSheet();

		return $sheet ? $this->sumRawTransactions($sheet) : ['masuk' => 0, 'keluar' => 0];
	}

	/**
	 * Monthly totals for all months in the Excel file, formatted for the chart.
	 */
	public function getAllMonthlyData(): array
	{
		$monthLabels = [
			1 => 'Jan 25',
			2 => 'Feb 25',
			3 => 'Mar 25',
			4 => 'Apr 25',
			5 => 'Mei 25',
			6 => 'Jun 25',
			7 => 'Jul 25',
			8 => 'Agu 25',
			9 => 'Sep 25',
			10 => 'Okt 25',
			11 => 'Nov 25',
			12 => 'Des 25',
		];

		$result = [];

		foreach ($this->sheetsByMonth() as $month => $name) {
			$sheet = $this->spreadsheet()->getSheetByName($name);
			$sum = $this->sumRawTransactions($sheet);

			$result[] = [
				'month' => $monthLabels[$month] ?? $name,
				'masuk' => $sum['masuk'],
				'keluar' => $sum['keluar'],
				'nilai_masuk' => 0,
				'nilai_keluar' => 0,
			];
		}

		return $result;
	}

	/**
	 * @return array{tanggal: string|null, varian: string, jumlah: int, keterangan: string}[]
	 */
	public function getRecentMasuk(int $limit = 5): array
	{
		$sheet = $this->getActiveSheet();

		if (!$sheet) {
			return [];
		}

		$entries = [];
		$highestRow = min((int) $sheet->getHighestRow(), 1001);

		for ($row = 3; $row <= $highestRow; $row++) {
			$varian = $sheet->getCell("B{$row}")->getValue();
			$jumlah = $sheet->getCell("C{$row}")->getValue();

			if ($varian === null && $jumlah === null) {
				break;
			}

			$entries[] = [
				'tanggal' => $this->excelDateToString($sheet->getCell("A{$row}")->getValue()),
				'varian' => (string) ($varian ?? ''),
				'jumlah' => is_numeric($jumlah) ? (int) $jumlah : 0,
				'keterangan' => (string) ($sheet->getCell("D{$row}")->getValue() ?? ''),
			];
		}

		return array_slice(array_reverse($entries), 0, $limit);
	}

	/**
	 * @return array{tanggal: string|null, varian: string, jumlah: int, keterangan: string}[]
	 */
	public function getRecentKeluar(int $limit = 5): array
	{
		$sheet = $this->getActiveSheet();

		if (!$sheet) {
			return [];
		}

		$entries = [];
		$highestRow = min((int) $sheet->getHighestRow(), 1001);

		for ($row = 3; $row <= $highestRow; $row++) {
			$varian = $sheet->getCell("H{$row}")->getValue();
			$jumlah = $sheet->getCell("I{$row}")->getValue();

			if ($varian === null && $jumlah === null) {
				break;
			}

			if ($varian !== null) {
				$entries[] = [
					'tanggal' => $this->excelDateToString($sheet->getCell("G{$row}")->getValue()),
					'varian' => (string) $varian,
					'jumlah' => is_numeric($jumlah) ? (int) $jumlah : 0,
					'keterangan' => (string) ($sheet->getCell("J{$row}")->getValue() ?? ''),
				];
			}
		}

		return array_slice(array_reverse($entries), 0, $limit);
	}
}
