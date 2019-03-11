<?php
/**
 * User: ADMIN
 * Date: 12/17/2018 9:44 PM
 */

namespace App\TechAPI;

use App\Enums\LeadState;
use App\Models\Appointment;
use App\Models\Lead;
use App\TechAPI\Api\SendBrandnameOtp;
use App\TechAPI\Auth\AccessToken;
use App\TechAPI\Auth\ClientCredentials;

class FptSms
{
    public function __construct()
    {
        Constant::configs([
            'mode'            => Constant::MODE_LIVE,
//            'mode'            => Constant::MODE_SANDBOX,
            'connect_timeout' => 15,
            'enable_cache'    => false,
            'enable_log'      => true,
            'log_path'        => app_path('/TechAPI/logs'),
        ]);

        $this->client = new Client(
            config('fpt.client_id'),
            config('fpt.client_secret'),

//            config('fpt.client_sandbox_id'),
//            config('fpt.client_sandbox_secret'),
            ['send_brandname_otp'] // array('send_brandname', 'send_brandname_otp')
        );
    }

    public function connectApi()
    {
        return new ClientCredentials($this->client);
    }

    private function sendBrandNameSms($message, $phone)
    {
        $arrMessage = [
            'BrandName' => 'YesVacation',
            'Phone'     => $phone,
            'Message'   => $message,
        ];

        $tech = new ClientCredentials($this->client);
// Khởi tạo đối tượng API với các tham số phía trên.
        $apiSendBrandName = new SendBrandnameOtp($arrMessage);

        try {
            // Thực thi API
            $arrResponse = $tech->execute($apiSendBrandName);

            // kiểm tra kết quả trả về có lỗi hay không
            if ( ! empty($arrResponse['error'])) {
                // Xóa cache access token khi có lỗi xảy ra từ phía server
                AccessToken::getInstance()->clear();

                // quăng lỗi ra, và ghi log
                dump($arrResponse);

                throw new Exception($arrResponse['error_description'], $arrResponse['error']);
            }

            return $arrResponse;
        } catch (\Exception $ex) {
            echo sprintf('<p>Có lỗi xảy ra:</p>');
            echo sprintf('<p>- Mã lỗi: %s</p>', $ex->getCode());
            echo sprintf('<p>- Mô tả lỗi: %s</p>', $ex->getMessage());

            return $ex->getMessage();
        }
    }

    public function sendRemindPayment($content, $phone = '')
    {
        return $this->sendBrandNameSms($content, $phone);
    }

    public function sendPaymentConfirmation($amount, $contractCode, $phone = '')
    {
        $amount      = number_format($amount);
        $baseContent = "Yes Vacations xac nhan Quy Thanh vien da thanh toan {$amount} VND Hop Dong {$contractCode} Chung toi mong som duoc cung Gia dinh tao ra nhung ky niem dang nho nhat trong nhung ky nghi sap toi Vui long LH 028 730 11118 de duoc ho tro ve dat phong Cac van de khac LH 028 730 11119";

        return $this->sendBrandNameSms($baseContent, $phone);
    }

    public function sendAnualFeeConfirmation($phone = '')
    {
        $baseContent = 'Yes Vacations xac nhan Quy Thanh vien da thanh toan Phi Quan ly/ Phi Thuong nien cua nam 2019. Vui long LH reservation@yesvacations.vn Hoac 028 730 11118 de duoc ho tro ve dat phong Hoac 028 730 11119 ve cac van de khac';

        return $this->sendBrandNameSms($baseContent, $phone);
    }

    public function sendWelcome($code, $phone = '')
    {
        $baseContent = "Yes Vacations xin gui loi chao don am ap den Quy Thanh Vien. Ma TV cua gia dinh la: {$code}. De duoc tu van, giai dap thac mac hoac cac ho tro khac lien quan den san pham, Quy Thanh Vien vui long lien he Tong Dai CSKH: 02871011119 Tran trong";

        return $this->sendBrandNameSms($baseContent, $phone);
    }

    public function sendRegisterConfirmation(Lead $lead, Appointment $appointment, $phone = '')
    {
        $env = \App::environment();

        if ( ! $phone || $lead->state == LeadState::MEMBER || $lead->state == LeadState::DEAD_NUMBER || $lead->state == LeadState::WRONG_NUMBER || $env != 'production') {
            return false;
        }

        //0912136032
        $appDatetime = optional($appointment->appointment_datetime)->format('d-m-Y H:i:s');
        $dayOfWeek   = now()->dayOfWeek;
        $weekMap     = [
            0 => 'G',
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
        ];
        $weekCode    = $weekMap[$dayOfWeek];
        try {
            $code = "YTM{$weekCode}" . random_int(1000, 9999);
        } catch (\Exception $e) {
            $code = "YTM{$weekCode}" . rand(1000, 9999);
        }

        // Khởi tạo các tham số của tin nhắn.
        $message = "Tran trong kinh mời GD {$lead->title}. {$lead->name} tham dự Su Kien Du Lich luc {$appDatetime} tai Toa Indochina, 4 Nguyen Dinh Chieu, P.DaKao, Q1 (Tang 13, Khu A).
Xin vui long mang theo CNMD de hoan thanh thu tuc dang ky.
Dua ma so sau cho le tan de xac nhan: {$code}

Xin cam on,
Hotline: 02873000887";

        return $this->sendBrandNameSms($message, $phone);

    }

    public function sendBrandnameOtpTest(Lead $lead, Appointment $appointment, $phone = '')
    {
        $env = \App::environment();

        //0912136032
        $tech        = new ClientCredentials($this->client);
        $appDatetime = optional($appointment->appointment_datetime)->format('d-m-Y H:i:s');
        $dayOfWeek   = now()->dayOfWeek;
        $weekMap     = [
            0 => 'G',
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
        ];
        $weekCode    = $weekMap[$dayOfWeek];
        try {
            $code = "YTM{$weekCode}" . random_int(1000, 9999);
        } catch (\Exception $e) {
            $code = "YTM{$weekCode}" . rand(1000, 9999);
        }

        // Khởi tạo các tham số của tin nhắn.
        $arrMessage = [
//            'BrandName' => 'FTI',
            'BrandName' => 'YesVacation',
            'Phone'     => $phone,
            'Message'   => "Tran trong kinh mời GD {$lead->title}. {$lead->name} tham dự Su Kien Du Lich luc {$appDatetime} tai Toa Indochina, 4 Nguyen Dinh Chieu, P.DaKao, Q1 (Tang 13, Khu A).
Xin vui long mang theo CNMD de hoan thanh thu tuc dang ky.
Dua ma so sau cho le tan de xac nhan: {$code}

Xin cam on,
Hotline: 02873000887",
        ];

        // Khởi tạo đối tượng API với các tham số phía trên.
        $apiSendBrandName = new SendBrandnameOtp($arrMessage);

        try {
            // Thực thi API
            $arrResponse = $tech->execute($apiSendBrandName);

            // kiểm tra kết quả trả về có lỗi hay không
            if ( ! empty($arrResponse['error'])) {
                // Xóa cache access token khi có lỗi xảy ra từ phía server
                AccessToken::getInstance()->clear();

                // quăng lỗi ra, và ghi log
                dump($arrResponse);

                throw new Exception($arrResponse['error_description'], $arrResponse['error']);
            }

            dump($arrResponse, $arrMessage, $env);

            return $arrResponse;
        } catch (\Exception $ex) {
            echo sprintf('<p>Có lỗi xảy ra:</p>');
            echo sprintf('<p>- Mã lỗi: %s</p>', $ex->getCode());
            echo sprintf('<p>- Mô tả lỗi: %s</p>', $ex->getMessage());
        }
    }
}
