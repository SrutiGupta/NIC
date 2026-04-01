<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportLgdAdministrativeData extends Command
{
    protected $signature = 'lgd:import-admin {--dir= : Absolute or relative path to administrative CSV directory}';

    protected $description = 'Import LGD administrative CSVs (state, district, subdistrict, blocks) without psql';

    public function handle(): int
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('Current DB_CONNECTION is not pgsql. Update .env and retry.');
            return self::FAILURE;
        }

        $dirOption = $this->option('dir');
        $defaultDir = base_path('../india-local-government-directory-main/administrative');
        $dir = $dirOption ? $this->normalizePath($dirOption) : $defaultDir;

        $files = [
            'states' => $dir . DIRECTORY_SEPARATOR . '1-state.csv',
            'districts' => $dir . DIRECTORY_SEPARATOR . '2-district.csv',
            'subdistricts' => $dir . DIRECTORY_SEPARATOR . '3-subdistrict.csv',
            'blocks' => $dir . DIRECTORY_SEPARATOR . 'blocks.csv',
        ];

        foreach ($files as $key => $path) {
            if (!is_file($path)) {
                $this->error("Missing {$key} CSV: {$path}");
                return self::FAILURE;
            }
        }

        DB::beginTransaction();
        try {
            DB::table('lgd_blocks')->delete();
            DB::table('lgd_subdistricts')->delete();
            DB::table('lgd_districts')->delete();
            DB::table('lgd_states')->delete();

            $stateCount = $this->importCsv($files['states'], 'lgd_states', [
                'serial_no',
                'state_code',
                'state_version',
                'state_name',
                'state_name_repeat',
                'census_2001_code',
                'census_2011_code',
                'state_or_ut',
            ]);

            $districtCount = $this->importCsv($files['districts'], 'lgd_districts', [
                'state_code',
                'state_name',
                'district_code',
                'district_name',
                'census_2001_code',
                'census_2011_code',
            ]);

            $subdistrictCount = $this->importCsv($files['subdistricts'], 'lgd_subdistricts', [
                'serial_no',
                'state_code',
                'state_name',
                'district_code',
                'district_name',
                'subdistrict_code',
                'subdistrict_version',
                'subdistrict_name',
                'census_2001_code',
                'census_2011_code',
            ]);

            $blockCount = $this->importCsv($files['blocks'], 'lgd_blocks', [
                'serial_no',
                'state_code',
                'state_name',
                'district_code',
                'district_name',
                'block_code',
                'block_version',
                'block_name',
                'block_name_repeat',
            ]);

            DB::commit();

            $this->info("Imported states: {$stateCount}");
            $this->info("Imported districts: {$districtCount}");
            $this->info("Imported subdistricts: {$subdistrictCount}");
            $this->info("Imported blocks: {$blockCount}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('LGD import failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function importCsv(string $path, string $table, array $columns): int
    {
        $file = fopen($path, 'r');
        if ($file === false) {
            throw new RuntimeException("Unable to open file: {$path}");
        }

        fgetcsv($file);

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < count($columns)) {
                continue;
            }

            $record = [];
            foreach ($columns as $index => $column) {
                $value = isset($row[$index]) ? trim((string) $row[$index]) : null;
                $record[$column] = ($value === '') ? null : $value;
            }

            $batch[] = $record;

            if (count($batch) >= 1000) {
                DB::table($table)->insert($batch);
                $count += count($batch);
                $batch = [];
            }
        }

        if ($batch !== []) {
            DB::table($table)->insert($batch);
            $count += count($batch);
        }

        fclose($file);

        return $count;
    }

    private function normalizePath(string $path): string
    {
        if (preg_match('/^[A-Za-z]:/', $path) === 1 || str_starts_with($path, '/') || str_starts_with($path, '\\\\')) {
            return $path;
        }

        return base_path($path);
    }
}
