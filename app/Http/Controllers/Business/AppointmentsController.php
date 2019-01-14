<?php

namespace App\Http\Controllers\Business;

use App\Exports\AppointmentExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Tables\TableFacade;
use App\Tables\Business\AppointmentTable;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AppointmentsController extends Controller
{
     /**
      * Tên dùng để phân quyền
      * @var string
      */
	 protected $name = 'appointment';

    /**
     * Hiển thị trang danh sách Appointment.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view( 'business.appointments.index' )->with('appointment', new Appointment);
    }

    /**
     * Lấy danh sách Appointment cho trang table ở trang index
     * @return string
     */
    public function table() {
    	return ( new TableFacade( new AppointmentTable() ) )->getDataTable();
    }

    /**
     * Trang tạo mới Appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('business.appointments.create', [
            'appointment' => new Appointment,
            'action' => route('appointments.store')
        ]);
    }

    /**
     * Lưu Appointment mới.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required'
		]);
        $requestData = $request->all();
        $appointment = Appointment::create($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully')
            ]);
        }

        return redirect(route('appointments.show', $appointment))->with('message', __( 'Data created successfully' ));
    }

    /**
     * Trang xem chi tiết Appointment.
     *
     * @param  Appointment $appointment
     * @return \Illuminate\View\View
     */
    public function show(Appointment $appointment)
    {
        return view('business.appointments.show', compact('appointment'));
    }

    /**
     * Trang cập nhật Appointment.
     *
     * @param  Appointment $appointment
     * @return \Illuminate\View\View
     */
    public function edit(Appointment $appointment)
    {
        return view('business.appointments.edit', [
            'appointment' => $appointment,
            'method' => 'put',
            'action' => route('appointments.update', $appointment)
        ]);
    }

    /**
     * Cập nhật Appointment tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->validate($request, [
			'name' => 'required'
		]);
        $requestData = $request->all();
        $appointment->update($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully')
            ]);
        }

        return redirect(route('appointments.show', $appointment))->with('message', __( 'Data edited successfully' ));
    }

    /**
     * Xóa Appointment.
     *
     * @param Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Appointment $appointment)
    {
        try {
        	  $appointment->delete();
        } catch ( \Exception $e ) {
            return $this->asJson( [
                'message' => "Error: {$e->getMessage()}"
            ], $e->getCode() );
        }

        return $this->asJson( [
            'message' => __('Data deleted successfully')
        ] );
    }

    /**
     * Xóa nhiều Appointment.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys() {
        try {
            $ids = \request()->get( 'ids' );
            Appointment::destroy( $ids );
        } catch ( \Exception $e ) {
            return $this->asJson( [
                'message' => "Error: {$e->getMessage()}"
            ], $e->getCode() );
        }

        return $this->asJson( [
            'message' => __( 'Data deleted successfully' )
        ] );
    }

    /**
     * Lấy danh sách Appointment theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function appointments() {
        $query         = request()->get('query', '');
        $page          = request()->get('page', 1);
        $leadId        = request()->get('leadId', '');
        $appointmentId = request()->get('appointmentId', '');
        $excludeIds    = request()->get('excludeIds', []);
        $offset        = ($page - 1) * 10;
        $appointments  = Appointment::query()->with(['lead', 'user']);

        $appointments->andFilterWhere([
            ['name', 'like', $query],
            ['id', '!=', $excludeIds],
            ['lead_id', '=', $leadId],
            ['id', '=', $appointmentId],
        ]);

        $totalCount   = $appointments->count();
        $appointments = $appointments->offset($offset)->limit(10)->get();

        return response()->json([
            'total_count' => $totalCount,
            'items'       => $appointments->toArray(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();

//        $appointmentIds = $filters['id'];
//        if ($appointmentIds) {
//            $appointmentIds = explode(',', $appointmentIds);
//        } else {
//            $appointmentIds = [];
//        }
//        $filters['id'] = $appointmentIds;

        return (new AppointmentExport($filters))->download('appointments_' . time() . '.xlsx');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formImport()
    {
        return view('business.appointments._form_import');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file_import' => 'required',
        ]);
        $userId    = $request->get('user_id');

        if ($request->hasFile('file_import')) {
            $fileImport = $request->file('file_import');
            $fileName   = $fileImport->getClientOriginalName();

            $inputFileType = 'Xlsx';

            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($fileImport);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $totalFail    = 0;
            $totalSuccess = 0;
            $datas        = $dataFails = [];
//            $provinces    = Province::get();
            $currentPhones = [];

            foreach ($sheetData as $rowIndex => $value) {
                $message = '';
                if ($rowIndex === 1) {
                    continue;
                }

                $title        = trim($value['A']);
                $name         = trim($value['B']);
                $email        = trim($value['C']);
                $birthday     = trim($value['D']);
                $address      = trim($value['E']);
                $provinceName = trim($value['F']);
                $phone        = trim($value['G']);

                if (empty($name)) {
                    $message = 'Tên lead bị rỗng.';
                }

                if ($message) {
                    $dataFails[] = [
                        'reason' => $message,
                        'row'    => $rowIndex,
                        'phone'  => $phone,
                    ];
                    $totalFail++;
                    continue;
                }

                $isPhoneUnique = Lead::isPhoneUnique($phone);

                if ( ! $isPhoneUnique || \in_array($phone, $currentPhones, true)) {
                    $totalFail++;
                    $message     = 'Lead đã tồn tại.';
                    $dataFails[] = [
                        'reason' => $message,
                        'row'    => $rowIndex,
                        'phone'  => $phone,
                    ];
                    continue;
                }

                try {

                    $customerAttributes = array_merge(compact('name', 'phone', 'email', 'title', 'address'), [
                        'created_at'  => now()->toDateTimeString(),
                        'birthday'    => null,
                        'province_id' => null,
                        'user_id'     => $userId,
                    ]);
                    if ($birthday) {
                        $birthday                       = Carbon::parse(trim($birthday))->toDateString();
                        $customerAttributes['birthday'] = $birthday;
                    }
                    if ($provinceName) {
                        $province = Province::where('name', 'like', "%$provinceName%")->first();
                        if ($province) {
                            $customerAttributes['province_id'] = $province->id;
                        }
                    }
                    $datas[]         = $customerAttributes;
                    $currentPhones[] = $phone;

                    $totalSuccess++;
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

            }
            Lead::insert($datas);
            $fileFailName = '';
            if ($dataFails) {
                $excel = new Spreadsheet();

                $sheet = $excel->setActiveSheetIndex(0);
                $sheet->setCellValue('A1', 'Dòng')
                      ->setCellValue('B1', 'Lí do')
                      ->setCellValue('C1', 'Số điện thoại');
                $row = 2;
                foreach ($dataFails as $dataFail) {
                    $sheet->setCellValue('A' . $row, $dataFail['row'])
                          ->setCellValue('B' . $row, $dataFail['reason'])
                          ->setCellValue('C' . $row, $dataFail['phone']);
                    $row++;
                }
                $excelWriter   = new Xlsx($excel);
                $fileNames     = explode('.', $fileName);
                $time          = time();
                $fileFailName  = "{$fileNames[0]}_{$time}_error.xlsx";
                $excelFileName = storage_path() . '/app/public/leads/' . $fileFailName;
                $excelWriter->save($excelFileName);
            }

            $textFail = $totalFail;
            if ($totalFail > 0) {
                $textFail = ' <a download href="' . asset("storage/leads/{$fileFailName}") . '" class=" m-link m--font-danger" title="' . __('Download error file') . '">' . $totalFail . '</a>';
            }

            return response()->json([
                'message' => 'File ' . $fileImport->getClientOriginalName() . __(' import successfully') . ". Số dòng thành công: {$totalSuccess}. Số dòng thất bại: {$textFail}",
            ]);
        }

        return response()->json([
            'message' => __('File not found'),
        ], 500);
    }
}