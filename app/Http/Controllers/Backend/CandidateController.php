<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\CandidateRequest;

use App\Models\Dzongkhag;
use App\Models\Constituency;
use App\Models\Party;
use App\Models\Candidate;
use Storage;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('backend.candidate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve dzongkhags, constituencies, and parties from the database
        $dzongkhags = Dzongkhag::all();
        $constituencies = Constituency::all();
        $parties = Party::all();

        // Retrieve constituency ID from the query string
        $constituencyId = request()->query('constituency_id');

        // Retrieve all candidates for the given constituency
        $candidates = Candidate::where('constituency_id', $constituencyId)->get();

        $selectedConstituency = Constituency::find($constituencyId);

        // Pass the data to the view
        return view('backend.candidate.create', compact('dzongkhags', 'constituencies', 'parties', 'candidates', 'constituencyId', 'selectedConstituency'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'constituency_id' => 'required|exists:constituencies,id',
        ]);

        // Extract common data
        $commonData = [
            'constituency_id' => $request->input('constituency_id'),
        ];

        $maxPartyId = collect($request->all())->filter(function ($value, $key) {
            return Str::startsWith($key, 'party_') && Str::endsWith($key, '_name');
        })->keys()->map(function ($key) {
            return intval(str_replace(['party_', '_name'], '', $key));
        })->max();

        // Iterate through party data
        for ($i = 1; $request->has("party_{$i}_name"); $i++) {

            $existingCandidate = Candidate::where([
                'constituency_id' => $commonData['constituency_id'],
                'party_id' => $i,
            ])->first();

            // // Delete the old profile image if it exists
            // if ($existingCandidate && !empty($existingCandidate->profile_image)) {
            //     Storage::disk('public')->delete($existingCandidate->profile_image);
            // }

            $partyData = [
                'party_id' => $i,
                'name' => $request->input("party_{$i}_name"),
                'dz_name' => $request->input("party_{$i}_dz_name")
            ];

            $profileImage = $request->file("party_{$i}_profile_image");



            if ($profileImage && $profileImage->isValid()) {
                // File is present and valid

                // Delete the old profile image if it exists
                if ($existingCandidate && !empty($existingCandidate->profile_image)) {
                    Storage::disk('public')->delete($existingCandidate->profile_image);
                }

                $profileImagePath = $profileImage->store('party_images', 'public');

                $partyData['profile_image'] = $profileImagePath;
            }

            // Combine common and party data
            $candidateData = array_merge($commonData, $partyData);

            // dd($candidateData);
            // Update or create a candidate record
            Candidate::updateOrCreate(
                ['constituency_id' => $candidateData['constituency_id'], 'party_id' => $i],
                $candidateData
            );
        }

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
