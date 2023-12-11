<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dzongkhag;
use App\Models\Constituency;
use App\Models\Party;

use App\Models\Vote;

class VoteController extends Controller
{
    //
    public function index()
    {
        $votes = Vote::all();
        return view('backend.vote.index', compact('votes'));
    }

    public function create()
    {
         // Retrieve dzongkhags and constituencies from the database
        $dzongkhags = Dzongkhag::all();
        $constituencies = Constituency::all();
        $parties = Party::all();

        // Pass the data to the view
        return view(
            'backend.vote.create',
            compact('dzongkhags','constituencies', 'parties')
        );
    }

    public function store(Request $request)
    {
        // Validation logic here

        // Vote::create($request->all());

        $data = $request->all();

        // Extract general information
        $dzongkhagId = $data['dzongkhag_id'];
        $constituencyId = $data['constituency_id'];

        // Iterate over the party data
        for ($i = 1; $i <= 2; $i++) {
            $partyId = $i;
            $postalKey = "party_{$i}_postal";
            $evmKey = "party_{$i}_evm";
            $totalKey = "party_{$i}_total";

            $partyData = [
                'constituency_id' => $constituencyId,
                'party_id' => $partyId,
                'evm' => $data[$evmKey],
                'postal_ballot' => $data[$postalKey],
                // Assuming 'total' is not directly stored in the database but calculated
            ];

            // Create or update the record in the 'votes' table
            Vote::updateOrCreate(
                ['constituency_id' => $constituencyId, 'party_id' => $partyId],
                $partyData
            );
        }

        return redirect()->route('admin.votes.index');
    }

    public function edit($id)
    {
        $vote = Vote::findOrFail($id);
        // You may need to pass the list of constituencies and parties to the view
        return view('backend.vote.edit', compact('vote'));
    }

    public function update(Request $request, $id)
    {
        // Validation logic here

        $vote = Vote::findOrFail($id);
        $vote->update($request->all());

        return redirect()->route('admin.vote.index');
    }

    public function destroy($id)
    {
        $vote = Vote::findOrFail($id);
        $vote->delete();

        return redirect()->route('admin.vote.index');
    }
}
