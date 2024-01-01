<?php

namespace App\Http\Livewire\Backend;

// use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use App\Domains\Auth\Models\User;
use App\Models\Candidate;
use App\Models\Constituency;
use App\Models\Party;
use App\Models\Dzongkhag;

class CandidatesTable extends DataTableComponent
{

    // /**
    //  * @var array|string[]
    //  */
    // public array $sortNames = [
    //     'constituency.name' => 'constituency.name',
    //     'party' => 'party.name',
    // ];

    // /**
    //  * @var array|string[]
    //  */
    // public array $filterNames = [
    //     'constituency_id' => 'User Type',
    //     'party' => 'E-mail Verified',
    // ];

    /**
     * @return Builder
     */
    public function query(): Builder
    {

        $query = Candidate::with('constituency.dzongkhag', 'party');

        return $query
            ->when($this->getFilter('search'), function ($query, $term) {
            $query->where(function ($subQuery) use ($term) {
                $subQuery->whereHas('constituency', function ($cq) use ($term) {
                    $cq->where('name', 'like', '%' . $term . '%');
                })
                ->orWhereHas('party', function ($pq) use ($term) {
                    $pq->where('name', 'like', '%' . $term . '%');
                });
            });
        })
        ->when($this->getFilter('dzongkhag'), function ($query, $dzongkhag) {
            // Assuming dzongkhag_id is a foreign key in the constituency table
            return $query->whereHas('constituency', function ($q) use ($dzongkhag) {
                $q->where('dzongkhag_id', $dzongkhag);
            });
        })
        ->when($this->getFilter('constituency'), function ($query, $constituency) {
            // Assuming constituency is a foreign key in the votes table
            return $query->where('constituency_id', $constituency);
        })
        ->when($this->getFilter('party'), function ($query, $party) {
            // Assuming party is a foreign key in the votes table
            return $query->where('party_id', $party);
        });

    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            'dzongkhag' => Filter::make('Dzongkhag')
                ->select(Dzongkhag::pluck('name', 'id')->prepend('Any', '')->toArray()),
            'constituency' => Filter::make('Constituency')
                ->select(Constituency::pluck('name', 'id')->prepend('Any', '')->toArray()),
            'party' => Filter::make('Party')
                ->select(Party::pluck('name', 'id')->prepend('Any', '')->toArray()),
        ];
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('ID'))
                ->sortable(),
            Column::make(__('Dzongkhag'), 'constituency.dzongkhag.name')
                ->searchable(),
            Column::make(__('Constituency'), 'constituency.name')
                ->searchable(),
            Column::make(__('Party'), 'party.name')
                ->searchable(),
            Column::make(__('Candidate Name (English)'), 'name')
                ->searchable(),
            Column::make(__('Candidate Name (Dzongkha)'), 'name')
                ->searchable(),
            Column::make(__('Profile Image'), 'profile_image'),
            Column::make(__('Actions')),
        ];
    }

    /**
     * @return string
     */
    public function rowView(): string
    {
        return 'backend.candidate.includes.row';
    }
}
