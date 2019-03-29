<?php

namespace App\Http\Controllers\Business;

use App\Enums\Confirmation;
use App\Enums\LeadState;
use App\Http\Controllers\Controller;
use App\Mail\AppointmentConfirmation;
use App\Models\Appointment;
use App\Models\Callback;
use App\Models\EventData;
use App\Models\HistoryCall;
use App\Models\Lead;
use App\Models\Province;
use App\Models\User;
use App\Tables\Business\LeadTable;
use App\Tables\TableFacade;
use App\TechAPI\FptSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;

class LeadsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'lead';

    /**
     * Hiển thị trang danh sách Lead.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('business.leads.index')->with('lead', new Lead);
    }

    /**
     * Lấy danh sách Lead cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new LeadTable()))->getDataTable();
    }

    /**
     * Trang tạo mới Lead.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('business.leads.create')->with('lead', new Lead);
    }

    /**
     * Lưu Lead mới.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'phone' => [
                'unique:leads',
                'sometimes',
                'nullable',
            ],
        ]);
        $requestData = $request->all();

        try {
            DB::beginTransaction();

            $phone = $requestData['phone'];

            $currentLead = Lead::where('phone', $phone)->get();

            if ($currentLead->isNotEmpty()) {
                $lead = $currentLead->first();
            } else {
                $lead = Lead::create($requestData);
            }

            if (isset($requestData['form']) && $requestData['form'] === 'reception') {
                $requestData['lead_id']              = $lead->id;
                $requestData['is_show_up']           = Confirmation::YES;
                $requestData['appointment_datetime'] = now()->toDateTimeString();

                $userId = null;
                if ( ! empty($requestData['user_id'])) {
                    $userId = $requestData['user_id'];
                } else {
                    if ( ! empty($requestData['ambassador'])) {
                        $lead = Lead::find($requestData['ambassador']);

                        $userId = $lead->user_id;
                    }
                }
                $appointment = Appointment::create(array_merge($requestData, [
                    'user_id' => $userId,
                ]));

                $requestData['appointment_id'] = $appointment->id;

                EventData::create($requestData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('Data created successfully'),
            ]);
        }

        return redirect(route('leads.index'))->with('message', __('Data created successfully'));
    }

    /**
     * Trang xem chi tiết Lead.
     *
     * @param Lead $lead
     *
     * @return \Illuminate\View\View
     */
    public function show(Lead $lead)
    {
        return view('business.leads.show', compact('lead'));
    }

    /**
     * Trang cập nhật Lead.
     *
     * @param Lead $lead
     *
     * @return \Illuminate\View\View
     */
    public function edit(Lead $lead)
    {
        return view('business.leads.edit', compact('lead'));
    }

    /**
     * Cập nhật Lead tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param Lead $lead
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Lead $lead)
    {
        $this->validate($request, [
            'name'  => 'required',
            'phone' => [
                'sometimes',
                function ($attribute, $value, $fail) use ($lead) {
                    $existedLead = Lead::wherePhone($value)->first();
                    if ($value && $existedLead && $existedLead->id !== $lead->id) {
                        return $fail(__(ucfirst($attribute)) . ' đã tồn tại trong cơ sở dữ liệu.');
                    }
                },
            ],
        ]);
        $requestData = $request->all();
        if (isset($requestData['appointment_id'])) {
            $appointmentId = $requestData['appointment_id'];
            $appointment   = Appointment::find($appointmentId);
            if ($appointment) {
                $appointment->update($requestData);
            }
        } else {
            if ( ! isset($requestData['is_private'])) {
                $requestData['is_private'] = 1;
            } else {
                $requestData['is_private'] = -1;
            }
        }

        $lead->update($requestData);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('Data edited successfully'),
            ]);
        }

        return redirect(route('leads.index'))->with('message', __('Data edited successfully'));
    }

    /**
     * Xóa Lead.
     *
     * @param Lead $lead
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Lead $lead)
    {
        try {
            $lead->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error: {$e->getMessage()}",
            ], $e->getCode());
        }

        return response()->json([
            'message' => __('Data deleted successfully'),
        ]);
    }

    /**
     * Xóa nhiều Lead.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys()
    {
        try {
            $ids = \request()->get('ids');
            Lead::destroy($ids);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error: {$e->getMessage()}",
            ], $e->getCode());
        }

        return response()->json([
            'message' => __('Data deleted successfully'),
        ]);
    }

    /**
     * Lấy danh sách Lead theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function leads()
    {
        $query      = request()->get('query', '');
        $phone      = request()->get('phone', '');
        $state      = request()->get('state', '');
        $leadId     = request()->get('leadId', '');
        $isNew      = request()->get('isNew', '');
        $page       = request()->get('page', 1);
        $excludeIds = request()->get('excludeIds', []);
        $offset     = ($page - 1) * 10;
        $leads      = Lead::query()->with(['user']);

        $leads->andFilterWhere([
            ['name', 'like', $query],
            ['phone', 'like', $phone],
            ['state', '=', $state],
            ['id', '!=', $excludeIds],
        ]);

        if ($leadId) {
            $leads->whereKey($leadId);
        }

        if ($isNew) {
            $lead = $leads->getAvailable()->first();
            if ($lead) {
                $lead->update([
                    'call_date' => now()->toDateTimeString(),
                    'user_id'   => auth()->id(),
                ]);

                return response()->json([
                    'total_count' => 1,
                    'items'       => [$lead->toArray()],
                ]);
            }

            return response()->json([
                'total_count' => 0,
                'items'       => [],
            ]);
        }

        $totalCount = $leads->count();
        $leads      = $leads->offset($offset)->limit(10)->get();

        return response()->json([
            'total_count' => $totalCount,
            'items'       => $leads->toArray(),
        ]);
    }

    /**
     * Lấy danh sách User theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function provinces()
    {
        $query       = request()->get('query', '');
        $page        = request()->get('page', 1);
        $excludeIds  = request()->get('excludeIds', []);
        $provinceIds = request()->get('provinceIds', []);
        $offset      = ($page - 1) * 10;
        $provinces   = Province::query()->select(['id', 'name']);

        $provinces->andFilterWhere([
            ['id', '!=', $excludeIds],
            ['name', 'like', $query],
        ]);

        if ($provinceIds) {
            $provinces->whereIn('id', $provinceIds);
        }

        $totalCount = $provinces->count();
        $provinces  = $provinces->offset($offset)->limit(10)->get();

        return response()->json([
            'total_count' => $totalCount,
            'items'       => $provinces->toArray(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formImport()
    {
        $lead = new Lead();

//        $leadStates = collect($lead->states)->filter(function($state, $key) {
//            return $key === 1 || $key === 10;
//        })->toArray();
//        unset($leadStates[8], $leadStates[7], $leadStates[9], $leadStates[11]);

        $leadStates = $lead->states;

        return view('business.leads._form_import', compact('leadStates'));
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
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $this->validate($request, [
            'file_import' => 'required',
        ]);
        $isPrivate = $request->get('is_private', 1);
        $userId    = $request->get('user_id');
        $state     = $request->get('state', 1);

        if ( ! $state) {
            $state = 1;
        }

        if ($request->hasFile('file_import')) {
            $fileImport = $request->file('file_import');
            $fileName   = $fileImport->getClientOriginalName();

            $inputFileType = 'Xlsx';
            File::setUseUploadTempDirectory(true);

            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);

            $spreadsheet   = $reader->load($fileImport);
            $sheetData     = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $totalFail     = $totalSuccess = 0;
            $currentPhones = $dataFails = [];

            $query      = DB::connection()->getPdo()->query('select phone from leads where deleted_at is null');
            $leadPhones = $query->fetchAll(\PDO::FETCH_COLUMN);
            $provinces  = Province::get();

            $chunks = collect($sheetData)->chunk(1000);

            DB::beginTransaction();
            try {
                foreach ($chunks as $chunkIndex => $chunk) {
                    $datas     = [];
                    $sheetData = $chunk->toArray();
                    foreach ($sheetData as $rowIndex => $value) {
//                        $message = '';
                        if ($chunkIndex === 0 && $rowIndex === 1) {
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
//                            $message = 'Tên lead bị rỗng.';
                            $name = 'no name';
                        }

//                        if ($message) {
//                            $dataFails[] = [
//                                'reason' => $message,
//                                'row'    => $rowIndex,
//                                'phone'  => $phone,
//                            ];
//                            $totalFail++;
//                            continue;
//                        }
                        $isPhoneUnique = true;
                        foreach ($leadPhones as $leadPhone) {
                            if ($leadPhone == $phone) {
                                $isPhoneUnique = false;
                                break;
                            }
                        }

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
                            $customerAttributes = array_merge(compact('name', 'phone', 'email', 'title', 'address', 'state'), [
                                'created_at'  => now()->toDateTimeString(),
                                'birthday'    => null,
                                'province_id' => null,
                                'is_private'  => $isPrivate,
                                'user_id'     => $userId,
                            ]);
                            if ($birthday) {
                                try {
                                    $birthday = Carbon::parse(trim($birthday))->toDateString();
                                } catch (Exception $e) {
                                    $birthday = null;
                                }
                                if ($birthday) {
                                    $customerAttributes['birthday'] = $birthday;
                                }
                            }
                            if ($provinceName) {
//                        $province = Province::where('name', 'like', "%$provinceName%")->first();
                                $province = $provinces->filter(function ($p) use ($provinceName) {
                                    return stripos($p->name, $provinceName);
                                })->first();
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
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }

//            $fileFailName = '';
//            if ($dataFails) {
//                $excel = new Spreadsheet();
//
//                $sheet = $excel->setActiveSheetIndex(0);
//                $sheet->setCellValue('A1', 'Dòng')
//                      ->setCellValue('B1', 'Lí do')
//                      ->setCellValue('C1', 'Số điện thoại');
//                $row = 2;
//                foreach ($dataFails as $dataFail) {
//                    $sheet->setCellValue('A' . $row, $dataFail['row'])
//                          ->setCellValue('B' . $row, $dataFail['reason'])
//                          ->setCellValue('C' . $row, $dataFail['phone']);
//                    $row++;
//                }
//                $excelWriter   = new Xlsx($excel);
//                $fileNames     = explode('.', $fileName);
//                $time          = time();
//                $fileFailName  = "{$fileNames[0]}_{$time}_error.xlsx";
//                $excelFileName = storage_path() . '/app/public/leads/' . $fileFailName;
//                $excelWriter->save($excelFileName);
//            }

//            $textFail = $totalFail;
//            if ($totalFail > 0) {
//                $textFail = ' <a download href="' . asset("storage/leads/{$fileFailName}") . '" class=" m-link m--font-danger" title="' . __('Download error file') . '">' . $totalFail . '</a>';
//            }

            return response()->json([
                'message' => 'File ' . $fileImport->getClientOriginalName() . __(' import successfully') . ". Số dòng thành công: {$totalSuccess}. Số dòng thất bại: {$totalFail}",
            ]);
        }

        return response()->json([
            'message' => __('File not found'),
        ], 500);
    }

    /**
     * @param Lead $lead
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formChangeState(Lead $lead)
    {
        $typeCall      = request()->get('typeCall');
        $callId        = request()->get('callId');
        $table         = request()->get('table');
        $customerPhone = request()->get('phone');

        $appointment = null;

        $leadStates = $lead->states;
        unset($leadStates[8], $leadStates[1], $leadStates[9], $leadStates[10], $leadStates[11]);

        if ($table === 'appointments' || $table === 're_app') {
            $appointment = Appointment::find($callId);
        }

        if ($table === 're_app') {
            $leadStates = [
                8 => LeadState::APPOINTMENT,
            ];
        }

        if ( ! $lead->exists) {
            $leadWithCustomPhone = Lead::wherePhone($customerPhone)->whereNotIn('state', [
                LeadState::DEAD_NUMBER,
                LeadState::OTHER_CITY,
                LeadState::CALL_LATER,
                LeadState::APPOINTMENT,
                LeadState::MEMBER,
            ])->first();

            if ($leadWithCustomPhone) {
                $lead = $leadWithCustomPhone;
            } else {
                $lead->phone = $customerPhone;
                $lead->name  = 'No Name';
                $lead->state = 11;
            }
        }

        $leadStates = collect($leadStates)->sortKeysDesc();

        return view('business.leads._form_change_state', ['lead' => $lead, 'typeCall' => $typeCall, 'callId' => $callId, 'table' => $table, 'appointment' => $appointment, 'leadStates' => $leadStates]);
    }

    public function formNewLead()
    {
        $lead = new Lead();

        return view('business.leads._form_new_lead_in_reception', [
            'lead' => $lead,
        ]);
    }

    /**
     * @param Lead $lead
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeState(Lead $lead, Request $request)
    {
        $newState    = $request->state;
        $comment     = $request->comment;
        $spouseName  = $request->spouse_name;
        $spousePhone = $request->spouse_phone;
        $email       = $request->email;
        $date        = $request->date;
        $time        = $request->time;

        $typeCall   = $request->get('typeCall', 1);
        $provinceId = $request->get('province_id');
        $leadName   = $request->get('name');
        $leadPhone  = $request->get('phone');

        //dùng de xóa thông tin cac bảng gọi lại
        $callId = $request->get('call_id', 1);
        $table  = $request->get('table', 1);

        //check thoi gian hẹn hop lệ hay không
        if (($typeCall == 4 || $newState == 8) || $lead->state == 8) {
            if ($date && $time) {
                $datetime = Carbon::createFromFormat('d-m-Y H:i', "$date $time");

                if ($datetime->isPast()) {
                    return response()->json([
                        'message' => 'Ngày hẹn không hợp lệ.',
                    ], 500);
                }
            }
        }

        if ($newState) {
            $userId = auth()->id();

            $leadDatas = [
                'state'     => $newState,
                'comment'   => $comment,
                'call_date' => now()->toDateTimeString(),
                'user_id'   => null,
                'name'      => $leadName,
                'phone'     => $leadPhone,
            ];

            if ($lead->email !== $email) {
                $leadDatas['email'] = $email;
            }
            if ($provinceId) {
                $leadDatas['province_id'] = $provinceId;
            }
            if ($lead->exists) {
                if ($lead->is_private == 1) {
                    unset($leadDatas['user_id']);
                }
                $lead->update($leadDatas);
            } else {
                $lead->fill($leadDatas)->save();
//                Lead::create($leadDatas);
            }
            $dateTime = '';
            if ($date && $time) {
                $dateTime = date('Y-m-d H:i:s', strtotime($date . $time));
            }
            //state = 8: lưu vào bảng appointment
            if ($newState == 8) {
                //note: chức năng reappointment
                $appoinmentId = $request->get('appointment_id');

                $oldAppointment = null;
                if ($appoinmentId || ($callId && ($table === 'appointments' || $table === 're_app'))) {
                    $appId          = $appoinmentId ?: $callId;
                    $oldAppointment = Appointment::find($appId);
                    $oldAppointment->update(['state' => -1]);
                }

                //note: nếu là chức năng re-app
                if ($table === 're_app') {
                    EventData::where(['lead_id' => $oldAppointment->lead_id])->update(['state' => -1]);
                }

                $appointmentDatas = [
                    'lead_id'      => $lead->id,
                    'user_id'      => $oldAppointment ? $oldAppointment->user_id : $userId,
                    'spouse_phone' => $spousePhone,
                    'spouse_name'  => $spouseName,
                    'code'         => Appointment::generateCode(),
                    'is_queue'     => 2,
                ];

                if ($dateTime) {
                    $appointmentDatas['appointment_datetime'] = $dateTime;
                }
                if ( ! Appointment::checkPhoneIsShowUp($leadPhone)) {
                    $appointment = Appointment::create($appointmentDatas);
                }

                if ($lead->email) {
                    $message = (new AppointmentConfirmation(compact('lead', 'appointment')))->onConnection('database')->onQueue('notification');
                    \Mail::to($email)->queue($message);
                }

                //Gửi sms cho KH
                if ($lead->phone && (
                        $lead->state != LeadState::DEAD_NUMBER &&
                        $lead->state != LeadState::WRONG_NUMBER &&
                        $lead->state != LeadState::MEMBER) && isset($appointment)
                ) {
                    $fptSms = new FptSms();
                    $fptSms->sendRegisterConfirmation($lead, $appointment, $lead->phone);
                }
            }

            //state = 7: lưu vào bảng callback
            if ($newState == 7) {
                $callbackDatas = [
                    'lead_id' => $lead->id,
                    'user_id' => $userId,
                ];
                if ($dateTime) {
                    $callbackDatas['callback_datetime'] = $dateTime;
                }
                $caller = Callback::create($callbackDatas);
            }

            $startCallTime = session('startCallTime');
            $diffInSeconds = now()->diffInSeconds($startCallTime);
            //nếu gọi callback => cập nhật đã gọi xong
            if ($table && $table === 'callbacks') {
                /** @noinspection NullPointerExceptionInspection */
                Callback::find($callId)->update([
                    'state' => 1,
                ]);
            }

            if ( ! empty($caller)) {
                $callerId = $caller->id;
                $callType = \get_class($caller);
            }

            //lưu bảng history_calls
            HistoryCall::create([
                'type'         => $typeCall,
                'lead_id'      => $lead->id,
                'user_id'      => $userId,
                'state'        => $lead->state,
                'comment'      => $comment,
                'time_of_call' => $diffInSeconds,
                'call_id'      => $callerId ?? null,
                'call_type'    => $callType ?? null,
            ]);
            session(['startCallTime' => now()->addSecond()]);
            /** @var User $user */
//            $user = auth()->user();
//            $user->removeCallCache();

            return response()->json([
                'message' => __('Data edited successfully'),
            ]);
        }

        return response()->json([
            'message' => __('Data edited unsuccessfully'),
        ]);
    }

    public function editAppointmentTime(Appointment $appointment, Request $request)
    {
        $dateTime = $request->get('dateTime');

        if ($dateTime) {
            $dateTime = date('Y-m-d H:i:s', strtotime($dateTime));
            $appointment->update([
                'appointment_datetime' => $dateTime,
            ]);
        }

        return response()->json([
            'message' => __('Data edited successfully'),
        ]);
    }

    public function editCallbackTime(Appointment $appointment, Request $request)
    {
        $datetime = $request->get('datetime');

        if ($datetime) {
            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
            $appointment->update([
                'callback_datetime' => $datetime,
            ]);
        }

        return response()->json([
            'message' => __('Data edited successfully'),
        ]);
    }

    public function resendEmail(Lead $lead, Appointment $appointment)
    {
        if ($lead->email) {
            $message = (new AppointmentConfirmation(compact('lead', 'appointment')))->onConnection('database')->onQueue('notification');
            \Mail::to($lead->email)->queue($message);

            return response()->json([
                'message' => 'Đã gửi lại email',
            ]);
        }

        return response()->json([
            'message' => 'Không thể gửi mail',
        ], 500);
    }

    public function saveHistoryCall(Lead $lead, Request $request)
    {
        $typeCall = $request->get('typeCall', 1);
        $userId   = auth()->id();

        $startCallTime = session('startCallTime');
        $diffInSeconds = now()->diffInSeconds($startCallTime);
        //lưu bảng history_calls
        HistoryCall::create([
            'type'         => $typeCall,
            'lead_id'      => $lead->id,
            'user_id'      => $userId,
            'state'        => $lead->state,
            'comment'      => '',
            'time_of_call' => $diffInSeconds,
        ]);
        session(['startCallTime' => now()->addSecond()]);

        return response()->json([
            'message' => __('Data edited successfully'),
        ]);
    }

    public function putCallCache(Lead $lead)
    {
        $typeCall = request()->get('typeCall');
        if ($typeCall) {
            /** @var User $user */
            $user = auth()->user();

            $user->putCallCache($lead, $typeCall);
        }
    }

    public function checkAvailableNewLead()
    {
        /** @var User $user */
        $user = auth()->user();

        $isLoadPrivateOnly = $user->isLoadPrivateOnly();

        if ($isLoadPrivateOnly) {
            $lead = Lead::where('user_id', $user->id)->where('state', LeadState::NEW_CUSTOMER)->where('is_private', 1)->first();
        } else {
            $lead = Lead::where('state', LeadState::NEW_CUSTOMER)->first();
        }

        if ( ! $lead) {
            return response()->json([
                'message' => 'Đã gọi hết khách mới',
            ]);
        }

        return response()->json([
            'message' => '',
        ]);
    }
}