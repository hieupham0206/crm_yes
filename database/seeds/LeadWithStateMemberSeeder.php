<?php

use Illuminate\Database\Seeder;

class LeadWithStateMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inputFileName = database_path('files/leads_state_member.xlsx');

        /** Load $inputFileName to a Spreadsheet Object  **/
        try {
            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            /**  Advise the Reader of which WorkSheets we want to load  **/
            $reader->setReadDataOnly(true);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $datas     = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $leadDatas = [];
            foreach ($datas as $key => $data) {
                $phone = $data['A'];
                $name  = 'member';

                $leadDatas[] = [
                    'name'  => $name,
                    'phone' => $phone,
                    'state' => 10,
                    'created_at' => now()->toDateTimeString()
                ];
            }
            \App\Models\Lead::insert($leadDatas);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            var_dump($e);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            var_dump($e);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
