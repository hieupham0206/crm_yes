<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Models\Callback;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CallbackExport implements FromView
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $callbacks = Callback::query()->filters($this->filters)->with(['user', 'lead']);

        if ( ! empty($this->filters['phone'])) {
            $phone = $this->filters['phone'];
            $callbacks->whereHas('lead', function ($q) use ($phone) {
                $q->where('phone', $phone);
            });
        }

        return view('business.callbacks._template_export', [
            'callbacks' => $callbacks->get(),
            'callback'  => new Callback(),
        ]);
    }
}