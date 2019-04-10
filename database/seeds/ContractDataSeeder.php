<?php

use App\Enums\ContractMembership;
use App\Enums\ContractRoomType;
use App\Enums\Gender;
use App\Models\Contract;
use App\Models\PaymentDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ContractDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function run()
    {
        $inputFileName = database_path('files/Input_HCM.xlsx');

        /** Load $inputFileName to a Spreadsheet Object  **/
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
//
        try {

            DB::beginTransaction();

            $datas       = $spreadsheet->getSheet(0)->toArray(null, true, true, true);
            $cities      = [
                'HCM'        => 30,
                'Vũng Tàu'   => 2,
                'Bình Dương' => 9,
                'Đak Lak'    => 16,
                'Đồng Nai'   => 19,
                'Long An'    => 40,
            ];
            $memberDatas = $contractDatas = $paymentDetailDatas = $memberIds = $contractIds = [];
            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    continue;
                }
                $name = $data['C'];

                if (empty($name)) {
                    break;
                }

                $memberId    = $data['A'];
                $memberIds[] = (int) $memberId;
                $title       = $data['B'];
                $gender      = $data['D'];
                $birthday    = $data['E'];
                $address     = $data['F'];
                $city        = $data['G'];

                $phone           = $data['H'];
                $email           = $data['I'];
                $identity        = $data['J'];
                $identityAddress = $data['K'];
                $identityDate    = $data['L'];
                $spouseTitle     = $data['M'];
                $spouseName      = $data['N'];
                $spousePhone     = $data['O'];
                $spouseBirthday  = $data['P'];
                $spouseEmail     = $data['Q'];
                $tmpAdress       = $data['U'];

                $memberDatas[] = [
                    'id'               => $memberId,
                    'name'             => $name,
                    'title'            => $title,
                    'email'            => $email,
                    'city'             => $city ? $cities[$city] : null,
                    'gender'           => $gender === 'Female' ? Gender::FEMALE : Gender::MALE,
                    'birthday'         => $birthday ? date('Y-m-d', Date::excelToTimestamp($birthday)) : '2018-01-01',
//                    'birthday'         => $birthday ? Date::excelToTimestamp($birthday) : null,
                    'address'          => $address,
                    'identity'         => $identity,
                    'identity_address' => $identityAddress,
                    'identity_date'    => $identityDate,

                    'spouse_title'    => $spouseTitle,
                    'spouse_name'     => $spouseName,
                    'spouse_phone'    => $spousePhone,
                    'spouse_birthday' => $spouseBirthday && $spouseBirthday !== '04/11(Lê Thái Hà)' ? date('Y-m-d', Date::excelToTimestamp($spouseBirthday)) : '2018-01-01',
//                    'spouse_birthday' => $spouseBirthday ? Date::excelToTimestamp($spouseBirthday) : null,
                    'spouse_email'    => $spouseEmail,
                    'temp_address'    => $tmpAdress,

                    'phone'      => $phone,
                    'created_at' => now()->toDateTimeString(),
                ];
            }

            \App\Models\Member::insert($memberDatas);

            $datas = $spreadsheet->getSheet(1)->toArray(null, true, true, true);

            $idx            = 0;
            $memberships    = ContractMembership::toArray();
            $contract       = Contract::latest()->first();
            $contractLastId = $contract ? $contract->id : 0;
            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    continue;
                }
                $contractLastId++;

                $memberId      = $memberIds[$idx];
                $contractIds[] = $contractLastId;

                $contractNo = $data['C'];
                $amount     = $data['D'];
                $netAmount  = $data['E'];
                $membership = $data['G'];
                $roomType   = $data['H'];
                $signedDate = $data['J'];
                $startDate  = $data['L'];
                $endTime    = $data['M'];
                $yearCost   = $data['N'];

                $signedDate = Date::excelToTimestamp($signedDate);
                $endTimeVal = '2018';

                if ($endTime) {
                    $endTimeVal = $endTime === 'Trọn đời' ? 0 : $endTime - date('Y', $signedDate);
                }

                $contractDatas[] = [
                    'id'          => $contractLastId,
                    'member_id'   => $memberId,
                    'amount'      => str_replace(',', '', $amount),
                    'net_amount'  => str_replace(',', '', $netAmount),
                    'state'       => 1,
                    'contract_no' => $contractNo,
                    'membership'  => $membership ? $memberships[Str::upper(trim($membership))] : null,
                    'room_type'   => $roomType === '1PN' ? ContractRoomType::ONE_BED : ContractRoomType::TWO_BED,
                    'signed_date' => $signedDate ? date('Y-m-d', $signedDate) : null,
                    'start_date'  => $startDate,
                    'end_time'    => $endTimeVal,
                    'year_cost'   => $yearCost,
                ];

                $idx++;
            }

            Contract::insert($contractDatas);

            $datas = $spreadsheet->getSheet(2)->toArray(null, true, true, true);
            $idx   = 0;

            $paymentCost = \App\Models\PaymentCost::create([
                'name'           => 'Tiền mặt',
                'cost'           => 0,
                'payment_method' => \App\Enums\PaymentMethod::CASH,
            ]);

            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    continue;
                }

                $contractId    = $contractIds[$idx / 4];
                $id            = $data['A'];
                $paytime       = $data['C'];
                $payDateReal   = $data['F'];
                $totalPaidReal = $data['G'];

                if ( ! $payDateReal && ! $totalPaidReal) {
                    continue;
                }

                try {
                    $payDateReal = ! empty($payDateReal) ? date('Y-m-d', Date::excelToTimestamp(trim($payDateReal))) : null;
                } catch (Exception $e) {
                    $payDateReal = '';
                }

                $paymentDetailDatas[] = [
                    'contract_id'     => $contractId,
                    'pay_time'        => $paytime,
                    'pay_date_real'   => $payDateReal,
                    'payment_cost_id' => $paymentCost->id,
                    'total_paid_real' => $totalPaidReal ? str_replace(',', '', $totalPaidReal) : 0,
                ];

                if ($id != '' && $id != 1) {
                    $idx++;
                }
            }
            PaymentDetail::insert($paymentDetailDatas);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
