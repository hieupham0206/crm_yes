<?php

namespace App\Tables\Admin;

use App\Models\Lead;
use App\Models\User;
use App\Tables\DataTable;

class LeadTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'leads.name';
                break;
            case '2':
                $column = 'leads.phone';
                break;
            case '3':
                $column = 'leads.state';
                break;
            case '4':
                $column = 'leads.comment';
                break;
            default:
                $column = 'leads.state';
                break;
        }

        return $column;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $this->column = $this->getColumn();
        $leads        = $this->getModels();
        $dataArray    = [];

        /** @var Lead[] $leads */
        foreach ($leads as $key => $lead) {
            $dataArray[] = [
                ++$key,
                $lead->name,
                $lead->phone,
                $lead->state_text,
                $lead->comment,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Lead[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        /** @var User $user */
        $user = auth()->user();

        $leads = Lead::query();

        $isLoadPrivateOnly = $user->isLoadPrivateOnly();

        if ($isLoadPrivateOnly) {
            $this->filters['user_id'] = $user->id;
            $leads = $leads->where(['is_private' => 1]);
        } else {
            $this->filters['user_id'] = null;
            $leads = $leads->where(['is_private' => -1]);
        }

        $this->totalFilteredRecords = $this->totalRecords = $leads->count();

        if ($this->isFilterNotEmpty) {
            $leads->filters($this->filters);

            $this->totalFilteredRecords = $leads->count();
        }

        return $leads->limit($this->length)->offset($this->start)
                     ->orderByRaw('state, call_date asc')->get();
    }
}