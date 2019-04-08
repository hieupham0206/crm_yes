<?php

use Illuminate\Database\Seeder;

class AdminitrativeUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inputFileName = database_path('files/Danh_sach_thanh_pho_08_04_2019.xls');

        /** Load $inputFileName to a Spreadsheet Object  **/
        try {
            $inputFileType = 'Xls';
            $units         = [];

            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Advise the Reader of which WorkSheets we want to load  **/
            $reader->setReadDataOnly(true);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $datas = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            foreach ($datas as $key => $data) {

                if ($key === 1) {
                    continue;
                }

                $cityName = $data['A'];
                $cityCode = $data['B'];

                $countyName = $data['C'];
                $countyCode = $data['D'];

                $wardName = $data['E'];
                $wardCode = $data['F'];

                $units[] = [
                    'city_name' => $cityName,
                    'city_code' => $cityCode,

                    'county_name' => $countyName,
                    'county_code' => $countyCode,

                    'ward_name' => $wardName,
                    'ward_code' => $wardCode,
                ];
            }

            $chunkDatas = collect($units)->chunk(10);

            foreach ($chunkDatas as $chunkData) {
                \App\Models\AdministrativeUnit::insert($chunkData->toArray());
            }

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            var_dump($e);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            var_dump($e);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
