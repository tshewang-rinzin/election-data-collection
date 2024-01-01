@extends('backend.layouts.app')

@section('title', __('Add/Update Candidate'))

@section('content')
    <x-forms.post :action="route('admin.candidates.store')" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Add/Update Candidates')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.candidates.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{
                    dzongkhags: {{ $dzongkhags }},
                    constituencies: {{ $constituencies }}
                }">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="dzongkhag_id" class="col-sm-2 col-form-label">@lang('Dzongkhag')</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-4">
                                    <select name="dzongkhag_id" class="form-control" required x-on:change="populateConstituencies($event.target.value)" {{ $selectedConstituency ? 'readonly': ''}}>
                                        <option value="">@lang('Select Dzongkhag')</option>
                                        @foreach($dzongkhags as $dzongkhag)
                                            <option value="{{ $dzongkhag->id }}" {{ (old('dzongkhag_id') == $dzongkhag->id || $selectedConstituency && $selectedConstituency->dzongkhag_id == $dzongkhag->id) ? 'selected' : '' }}>
                                                {{ $dzongkhag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="constituency_id" class="col-sm-2 col-form-label">@lang('Constituency')</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <select name="constituency_id" id="constituency" class="form-control" required x-on:change="updateSelectedConstituency()" {{ request('constituency_id') ? 'readonly': '' }}>
                                                <option value="">@lang('Select Constituency')</option>
                                                @foreach($constituencies as $constituency)
                                                    <option value="{{ $constituency->id }}" {{ (old('constituency_id') == $constituency->id || request('constituency_id') == $constituency->id) ? 'selected' : '' }}>
                                                        {{ $constituency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($parties as $party)
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <img src="{{ $party->logo }}" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h5 class="card-title">@lang("{$party->name} Candidate")</h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary" id="selected-constituency"></h6>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <label for="{{ 'party_' . $party->id . '_name' }}" class="">@lang("Candidate Name in English")</label>
                                                <input type="text" name="{{ 'party_' . $party->id . '_name' }}" id="{{ 'party_' . $party->id . '_name' }}" class="form-control" required
                                                    value="{{ old('party_' . $party->id . '_name', $candidates->where('party_id', $party->id)->first()->name ?? '') }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <label for="{{ 'party_' . $party->id . '_name' }}" class="">@lang("Candidate Name in Dzongkha")</label>
                                                <input type="text" name="{{ 'party_' . $party->id . '_dz_name' }}" id="{{ 'party_' . $party->id . '_dz_name' }}" class="form-control" required
                                                    value="{{ old('party_' . $party->id . '_dz_name', $candidates->where('party_id', $party->id)->first()->dz_name ?? '') }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="{{ 'party_' . $party->id . '_profile_image' }}" class="">@lang("Profile Picture")</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="file" name="{{ 'party_' . $party->id . '_profile_image' }}" id="{{ 'party_' . $party->id . '_profile_image' }}" class="form-control-file" accept="image/*" {{ !$selectedConstituency ?? required }}>
                                                    @if($candidates->where('party_id', $party->id)->isNotEmpty())
                                                        @if(!empty($candidates->where('party_id', $party->id)->first()->profile_image))
                                                            <img src="{{ asset('storage/' . $candidates->where('party_id', $party->id)->first()->profile_image) }}" alt="Profile Image" class="img-thumbnail ml-2" style="max-width: 100px;">
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-lg btn-primary float-right" type="submit">@lang('Save')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection

@push(
    'after-scripts'
)

<!-- hihi -->
<script>

        function populateConstituencies(dzongkhagId) {
            const constituencies = @json($constituencies);

            const filteredConstituencies = constituencies.filter(con => con.dzongkhag_id == dzongkhagId);
            const constituencySelect = document.getElementById('constituency');

            // Clear existing options
            constituencySelect.innerHTML = '<option value="">@lang("Select Constituency")</option>';

            // Populate options based on filtered constituencies
            filteredConstituencies.forEach(constituency => {
                const option = document.createElement('option');
                option.value = constituency.id;
                option.text = constituency.name;
                constituencySelect.appendChild(option);
            });

        }

        function updateSelectedConstituency() {
            const selectedConstituency = document.getElementById('constituency');
            const selectedLabel = selectedConstituency.options[selectedConstituency.selectedIndex].text;

            const selectedConstituencies = document.querySelectorAll('[id^="selected-constituency"]');
            selectedConstituencies.forEach(element => {
                // Replace 'selectedLabel' with the actual label you want to set
                element.innerText = selectedLabel + " constituency";
            });
            // document.getElementById('selected-constituency').innerText = selectedLabel;
        }
</script>

@endpush
