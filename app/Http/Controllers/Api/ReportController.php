<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Dzongkhag;
use App\Models\Constituency;
use App\Models\Party;

use App\Models\Vote;
use DB;

class ReportController
{
    //

    public function byConstituencies(){
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

        return $data;

    }

    public function byConstituenciesWithVoteType() {

        $constituencies = DB::table('constituencies')->pluck('name', 'id');
    $parties = DB::table('parties')->get();

    $chartData = [];

    foreach ($constituencies as $constituencyId => $constituencyName) {
        $votesData = Vote::where('constituency_id', $constituencyId)
            ->groupBy('party_id')
            ->select('party_id',
                DB::raw('SUM(evm) as total_evm'),
                DB::raw('SUM(postal_ballot) as total_postal_ballot')
            )
            ->get();

        $data = [];

        foreach ($parties as $party) {
            $partyVotes = $votesData->where('party_id', $party->id)->first();
            $data[] = [
                'label' => $party->name,
                'evm' => $partyVotes ? $partyVotes->total_evm : 0,
                'postal_ballot' => $partyVotes ? $partyVotes->total_postal_ballot : 0,
            ];
        }

        $chartData[$constituencyId] = [
            'label' => $constituencyName,
            'data' => $data,
        ];
    }

    return response()->json(['chartData' => $chartData, 'constituencies' => $constituencies, 'parties' => $parties]);



        // Fetch data from the database
    $results = Vote::with(['constituency', 'party'])
        ->orderBy('constituency_id')
        ->get();

    // return $results;

    // Group results by constituency and party
    $groupedResults = $results->groupBy(['constituency_id', 'party_id']);

    // return $groupedResults;
    // Get all constituencies and parties
    $constituencies = Constituency::pluck('name', 'id')->all();
    $parties = Party::all();

    // Prepare data for the chart
    $chartData = [];

    foreach ($constituencies as $constituencyId => $constituencyName) {
        $chartData[$constituencyId]['label'] = $constituencyName;

        foreach ($parties as $party) {
            $partyId = $party->id;

            // Check if the group exists
            if (isset($groupedResults[$constituencyId][$partyId])) {
                // Initialize sums
                $evmSum = 0;
                $postalBallotSum = 0;

                // Iterate through the group
                foreach ($groupedResults[$constituencyId][$partyId] as $vote) {
                    $evmSum += $vote->evm;
                    $postalBallotSum += $vote->postal_ballot;
                }



                $chartData[$constituencyId]['data'][] = [
                    'label' => $party->name,
                    'evm' => $evmSum,
                    'postal_ballot' => $postalBallotSum,
                ];
            } else {
                // If the group doesn't exist, set sums to 0
                $chartData[$constituencyId]['data'][] = [
                    'label' => $party->name,
                    'evm' => 0,
                    'postal_ballot' => 0,
                ];
            }
        }
    }
        return response()->json(['chartData' => $chartData, 'constituencies' => $constituencies, 'parties' => $parties]);
    }

    public function getWinByConstituenciesData(Request $request)
    {
        // Fetch data from the votes table
        $data = Vote::with(['constituency', 'party'])->get();

        // Process data to calculate wins for each party in each constituency
        $partyWins = [];
        $constituenciesList = [];

        foreach ($data as $entry) {
            $constituencyId = $entry->constituency_id;
            $partyName = $entry->party->name;

            // Calculate combined votes for the current party in the current constituency
            $totalVotes = $entry->evm + $entry->postal_ballot;

            // Check if this party has more votes than the current winner in this constituency
            if (!isset($partyWins[$constituencyId][$partyName]) || $totalVotes > $partyWins[$constituencyId][$partyName]) {
                $partyWins[$constituencyId][$partyName] = $totalVotes;

                // Keep track of the constituency if the current party has the maximum votes
                $constituenciesList[$partyName][$constituencyId] = $entry->constituency->name;
            }
        }

        // Count the number of constituencies won by each party
        $partyWinsCount = [];
        foreach ($partyWins as $constituencyWins) {
            $maxVotes = max($constituencyWins);
            foreach ($constituencyWins as $party => $votes) {
                if ($votes === $maxVotes) {
                    $partyWinsCount[$party] = isset($partyWinsCount[$party]) ? $partyWinsCount[$party] + 1 : 1;
                }
            }
        }

        return response()->json(['partyWinsCount' => $partyWinsCount, 'constituenciesList' => $constituenciesList]);

    }

    public function votesByConstituency(Request $request)
    {
        $constituency = Constituency::with(['dzongkhag', 'candidates.party'])->find($request->constituency_id);
        // Fetch data from the votes table
        $data = Vote::with(['constituency', 'party'])->where(['constituency_id'=>$request->constituency_id])->orderBy('party_id')->get();

        // Process data to calculate total votes, EVM votes, and postal ballot votes for each party
        $partyVotes = [];

        foreach ($data as $entry) {
            $partyName = $entry->party->name;

            // Extract relevant vote counts
            $evmVotes = $entry->evm;
            $postalBallotVotes = $entry->postal_ballot;
            $totalVotes = $evmVotes + $postalBallotVotes;

            // Accumulate votes for each party
            if (!isset($partyVotes[$partyName])) {
                $partyVotes[$partyName] = [
                    'total_votes' => 0,
                    'evm_votes' => 0,
                    'postal_ballot_votes' => 0,
                ];
            }
            $partyVotes[$partyName]['party_id'] = $entry->party->id;
            $partyVotes[$partyName]['logo'] = $entry->party->logo;
            $partyVotes[$partyName]['total_votes'] += $totalVotes;
            $partyVotes[$partyName]['evm_votes'] += $evmVotes;
            $partyVotes[$partyName]['postal_ballot_votes'] += $postalBallotVotes;
            $partyVotes[$partyName]['color_code'] = $entry->party->color_code;
            $partyVotes[$partyName]['abbreviation'] = $entry->party->abbreviation;

        }



        return response()->json(['partyVotes' => $partyVotes, 'constituency' => $constituency ]);

    }

    public function countConstituencyWins(Request $request)
    {
        // Fetch data from the votes table
        $data = Vote::with(['constituency', 'party'])->get();

        // Process data to calculate wins for each party in each constituency
        $partyWins = [];

        foreach ($data as $entry) {
            $constituencyId = $entry->constituency_id;
            $partyName = $entry->party->name;
            $partyAbbreviation = $entry->party->abbreviation; // Add this line
            $partyColorCode = $entry->party->color_code; // Add this line

            // Calculate combined votes for the current party in the current constituency
            $totalVotes = $entry->evm + $entry->postal_ballot;

            // Check if this party has more votes than the current winner in this constituency
            if (!isset($partyWins[$constituencyId][$partyName]) || $totalVotes > $partyWins[$constituencyId][$partyName]['votes']) {
                $partyWins[$constituencyId][$partyName] = [
                    'votes' => $totalVotes,
                    'abbreviation' => $partyAbbreviation,
                    'color_code' => $partyColorCode,
                ];
            }
        }

        // Count the number of constituencies won by each party
        $partyWinsCount = [];

        foreach ($partyWins as $constituencyWins) {
            $maxVotes = max(array_column($constituencyWins, 'votes'));
            foreach ($constituencyWins as $party => $partyData) {
                if ($partyData['votes'] === $maxVotes) {
                    $partyWinsCount[] = [
                        'name' => $party,
                        'abbreviation' => $partyData['abbreviation'],
                        'color_code' => $partyData['color_code'],
                        'value' => 1,
                    ];
                }
            }
        }

        // Sum the number of constituencies won for each party
        $partyWinsCount = array_reduce($partyWinsCount, function ($carry, $item) {
            $key = array_search($item['name'], array_column($carry, 'name'));
            if ($key !== false) {
                $carry[$key]['value']++;
            } else {
                $carry[] = $item;
            }
            return $carry;
        }, []);

        return response()->json(['partyWinsCount' => $partyWinsCount]);
    }
}
