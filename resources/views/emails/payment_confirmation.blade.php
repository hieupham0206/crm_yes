@component('mail::message')
    Kính gửi Quý Thành viên,

    Yes Vacations xác nhận Quý Thành viên đã thanh toán {{ number_format($amount) }}.của hợp đồng {{ $contract->contract_no }} ký [ngày {{ $contract->signed_date->format('d') }} tháng {{ $contract->signed_date->format('m') }} năm {{$contract->signed_date->format('Y') }}]
    Chúng tôi mong sớm được cùng Gia đình tạo ra những kỷ niệm đáng nhớ nhất trong những kỳ nghỉ sắp tới.

    Thông tin về thủ tục đặt phòng, Quý Thành viên vui lòng liên hệ đến địa chỉ Email: reservation@yesvacations.vn Hoặc Số điện thoại: 028.730.1111.8

    Thông tin khác vui lòng liên hệ:
    TP. HCM: clientservice1@yesvacations.vn
    Hà Nội: hn.clientservice2@yesvacations.vn

    Trân trọng,
    CÔNG TY TNHH YES INTERNATIONAL
@endcomponent