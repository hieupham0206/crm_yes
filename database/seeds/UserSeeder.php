<?php

class UserSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'username'       => 'admin',
            'email'          => 'hieu.pham@cloudteam.vn',
            'password'       => \Hash::make(123456),
            'remember_token' => str_random(10),
            'use_otp'        => 0,
        ]);

        if ($user) {
            $user->assignRole('Admin');
        }

//        $roles = \App\Models\Role::whereKeyNot(1)->get();
//        factory(\App\Models\User::class, 50)->create()->each(function ($user) use ($roles) {
//            $role = $roles->random();
//
//            $user->assignRole($role->name);
//        });

        $inputFileName = database_path('files/DanhSachTele.xlsx');

        /** Load $inputFileName to a Spreadsheet Object  **/
        try {
            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            /**  Advise the Reader of which WorkSheets we want to load  **/
            $reader->setReadDataOnly(true);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $datas = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    $roleId = 4;
                } else {
                    $roleId = 6;
                }
                $name     = $data['A'];
                $names    = explode('-', str_slug($name));
                $username = '';
                foreach ($names as $nameKey => $empName) {
                    if (++$nameKey === count($names)) {
                        $username .= $empName;
                    } else {
                        $username .= substr($empName, 0, 1);
                    }
                }

//                $firstDayWork = \Carbon\Carbon::parse($data['C'])->toDateString();
                $firstDayWork = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($data['C']);

                $userData = [
                    'name'           => $name,
                    'username'       => $username,
                    'first_day_work' => date('Y-m-d', $firstDayWork),
                    'password'       => \Hash::make('1234@Abcd'),
                    'remember_token' => str_random(10),
                ];

                $user = \App\Models\User::create($userData);

                $user->assignRole([$roleId]);
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