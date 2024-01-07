<?php

namespace App\Http\Controllers\Backend;

use App\Models\Dzongkhag;
use App\Models\Constituency;
use App\Models\Party;
use App\Models\Vote;

/**
 * Class ElectionResultController.
 */
class ElectionResultController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        // Fetch data from the database
        $results = Vote::with(['constituency', 'party'])
            ->orderBy('constituency_id')
            ->get();

        // Group results by constituency
        $groupedResults = $results->groupBy('constituency_id');

        // Get all constituencies
        $constituencies = Constituency::pluck('name', 'id')->all();

        // Get all parties
        $parties = Party::all();

        // Prepare data for the chart
        $chartData = [];

        foreach ($constituencies as $constituencyId => $constituencyName) {
            $chartData[$constituencyId]['label'] = $constituencyName;

            foreach ($parties as $party) {
                $partyId = $party->id;

                $result = $results
                    ->where('constituency_id', $constituencyId)
                    ->where('party_id', $partyId)
                    ->first();

                $chartData[$constituencyId]['data'][] = $result ? $result->evm + $result->postal_ballot : 0;
            }
        }

        $data = ["chartData"=> $chartData, "constituencies" => $constituencies, "parties"=> $parties];

        // return $data;

        // Pass data to the view
        return view('backend.election-result.index', compact('chartData', 'constituencies', 'parties'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Retrieve dzongkhags and constituencies from the database
        $dzongkhags = Dzongkhag::all();
        $constituencies = Constituency::all();
        $parties = Party::all();

        // Pass the data to the view
        return view(
            'backend.election-result.create',
            compact('dzongkhags','constituencies', 'parties')
        );
    }


    /**
     * Store a new flight in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request...

        return redirect()->route('admin.election-result.show', $election_result)->withFlashSuccess(__('The user was successfully created.'));
    }


    public function listConstituencies(){

        // Get all constituencies, both published and unpublished
        $allConstituencies = Constituency::orderBy('publish_result', 'desc')
                                ->orderBy('updated_at', 'desc')
                                ->get();

        return view('backend.election-result.list-constituencies', compact('allConstituencies'));

    }

    public function publishConstituencyResult($constituencyId){

        $constituency = Constituency::findOrFail($constituencyId);

        // Check if there are any votes for the constituency
        if ($constituency->votes->isEmpty()) {
            return redirect()->route('admin.election-result.list-constituencies')
                ->with('error', 'Cannot publish result for ' . $constituency->name . '. There are no votes.');
        }

        $constituency->update(['publish_result' => 1]);

        return redirect()->route('admin.election-result.list-constituencies')
            ->with('success', 'Result for ' . $constituency->name . ' published successfully.');
    }
}
