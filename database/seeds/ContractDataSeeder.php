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
        $inputFileName = database_path('files/Input.xlsx');

        /** Load $inputFileName to a Spreadsheet Object  **/
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
//
        try {

            DB::beginTransaction();

            $datas       = $spreadsheet->getSheet(0)->toArray(null, true, true, true);
            $cities      = [
                'Hồ Chí Minh' => 30,
                'Vũng Tàu'    => 2,
                'Bình Dương'  => 9,
                'Đak Lak'     => 16,
                'Đồng Nai'    => 19,
                'Long An'     => 40,
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
                $birthday    = str_replace('/', '-', $data['E']);
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

                try {
                    $birthdayVal       = ! empty($birthday) ? date('Y-m-d', Date::excelToTimestamp($birthday)) : '2018-01-01';
                    $spouseBirthdayVal = ! empty($spouseBirthday) && $spouseBirthday !== '04/11(Lê Thái Hà)' ? date('Y-m-d', Date::excelToTimestamp($spouseBirthday)) : '2018-01-01';

                } catch (Exception $e) {
                    $birthdayVal       = date('Y-m-d', strtotime($birthday));
                    $spouseBirthdayVal = date('Y-m-d', strtotime($spouseBirthday));
                }

                $memberDatas[] = [
                    'id'               => $memberId,
                    'name'             => $name,
                    'title'            => $title,
                    'email'            => $email,
                    'city'             => $city ? $cities[$city] : null,
                    'gender'           => $gender === 'Female' ? Gender::FEMALE : Gender::MALE,
                    'birthday'         => $birthdayVal,
//                    'birthday'         => $birthday ? Date::excelToTimestamp($birthday) : null,
                    'address'          => $address,
                    'identity'         => $identity,
                    'identity_address' => $identityAddress,
                    'identity_date'    => $identityDate,

                    'spouse_title'    => $spouseTitle,
                    'spouse_name'     => $spouseName,
                    'spouse_phone'    => $spousePhone,
                    'spouse_birthday' => $spouseBirthdayVal,
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
            $contractStates = [
                'Full payment' => \App\Enums\ContractState::FULL,
                'Cancel'       => \App\Enums\ContractState::CANCEL,
                'Instalment'   => \App\Enums\ContractState::INSTALLMENT,
                'Problem deal' => \App\Enums\ContractState::PROBLEM,
                'Upgrade'      => \App\Enums\ContractState::UPGRADE,
            ];

            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    continue;
                }
                $contractLastId++;

//                $memberId      = $memberIds[$idx];
                $contractIds[] = $contractLastId;

                $memberId            = $data['B'];
                $contractNo          = $data['C'];
                $amount              = $data['D'];
                $amountAfterDiscount = $data['E'];
                $netAmount           = $data['F'];
                $membership          = $data['H'];
                $roomType            = $data['I'];
                $limit               = $data['J'];
                $signedDate          = $data['K'];
                $startDate           = $data['M'];
                $endTime             = $data['N'];
                $yearCost            = $data['O'];

                $totalPayment = $data['Q'];
                $status       = $data['R'];
                $comment      = $data['S'];

                $signedDate = Date::excelToTimestamp($signedDate);

                $contractDatas[] = [
                    'id'                    => $contractLastId,
                    'member_id'             => $memberId,
                    'amount'                => str_replace(',', '', $amount),
                    'amount_after_discount' => str_replace(',', '', $amountAfterDiscount),
                    'net_amount'            => str_replace(',', '', $netAmount),
                    'contract_no'           => $contractNo,
                    'membership'            => $membership ? $memberships[Str::upper(trim($membership))] : null,
                    'room_type'             => $roomType === '1' ? ContractRoomType::ONE_BED : ContractRoomType::TWO_BED,
                    'limit'                 => $limit,
                    'signed_date'           => $signedDate ? date('Y-m-d', $signedDate) : null,
                    'start_date'            => "$startDate-01-01",
                    'end_time'              => $endTime,
                    'year_cost'             => $yearCost,
                    'total_payment'         => str_replace(',', '', $totalPayment),
                    'state'                 => $status ? $contractStates[$status] : \App\Enums\ContractState::PROBLEM,
                    'comment'               => $comment,
                ];

                $idx++;
            }

            Contract::insert($contractDatas);

            $datas = $spreadsheet->getSheet(2)->toArray(null, true, true, true);
            $idx   = -1;

            $paymentCost = \App\Models\PaymentCost::firstOrCreate([
                'name'           => 'Tiền mặt',
                'cost'           => 0,
                'payment_method' => \App\Enums\PaymentMethod::CASH,
            ]);

            foreach ($datas as $key => $data) {
                if ($key === 1) {
                    continue;
                }

                $contractNo = $data['B'];
                if ($contractNo) {
                    $idx++;
                }

                $contractId    = $contractIds[$idx];
                $paytime       = $data['C'];
                $payDateReal   = $data['F'];
                $totalPaidReal = $data['G'];

                if (( ! $payDateReal && ! $totalPaidReal) || ($payDateReal == 0 && $totalPaidReal == 0) || $payDateReal == '00/01/1900') {
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
                    'pay_date'        => $payDateReal,
                    'payment_cost_id' => $paymentCost->id,
                    'total_paid_real' => $totalPaidReal ? str_replace([',', '.'], '', $totalPaidReal) : 0,
                ];
            }
            PaymentDetail::insert($paymentDetailDatas);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
