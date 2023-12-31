@extends('backend.layouts.app')

@section('title', __('Submit Election Result'))

@section('content')
    <x-forms.post :action="route('admin.votes.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Submit Election Result')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
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
                                        <select name="dzongkhag_id" class="form-control" required x-on:change="populateConstituencies($event.target.value)">
                                            <option value="">@lang('Select Dzongkhag')</option>
                                            @foreach($dzongkhags as $dzongkhag)
                                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->name }}</option>
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
                                                <select name="constituency_id" id="constituency" class="form-control" required x-on:change="updateSelectedConstituency()">
                                                    <option value="">@lang('Select Constituency')</option>
                                                </select>
                                            </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div style="display:none" id="vote-form">
                    @foreach($parties as $party)
                        <div class="card">
                            <div class="row g-0">
                            <div class="col-md-2">
                                <img src="{{ $party->logo }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-10">
                            <div class="card-body">
                                <h5 class="card-title">@lang("{$party->name} Votes")</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary" id="selected-constituency"></h6>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <hr />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="{{ 'party_' . $party->id . '_postal' }}" class="">@lang("Postal Ballot")</label>
                                        <input type="number" name="{{ 'party_' . $party->id . '_postal' }}" id="{{ 'party_' . $party->id . '_postal' }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="{{ 'party_' . $party->id . '_votes' }}" class="">@lang("EVM")</label>
                                        <input type="number" name="{{ 'party_' . $party->id . '_evm' }}" id="{{ 'party_' . $party->id . '_evm' }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="{{ 'party_' . $party->id . '_total' }}" class="">@lang("Total")</label>
                                        <input type="number" name="{{ 'party_' . $party->id . '_total' }}" id="{{ 'party_' . $party->id . '_total' }}" class="form-control" required readonly>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <script>
                            document.getElementById('{{ 'party_' . $party->id . '_postal' }}').addEventListener('input', updateTotal);
                            document.getElementById('{{ 'party_' . $party->id . '_evm' }}').addEventListener('input', updateTotal);

                            function updateTotal() {
                                const postal = parseFloat(document.getElementById('{{ 'party_' . $party->id . '_postal' }}').value) || 0;
                                const evm = parseFloat(document.getElementById('{{ 'party_' . $party->id . '_evm' }}').value) || 0;
                                const total = postal + evm;
                                document.getElementById('{{ 'party_' . $party->id . '_total' }}').value = total;
                            }
                        </script>
                    @endforeach
                    </div>
                </div>
            </x-slot>

                <x-slot name="footer">
                    <div style="display:none" id="submit-button">
                    <button class="btn btn-sm btn-primary" type="submit">@lang('Submit')</button>
                    </div>
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
            document.getElementById('vote-form').style.display = "none";
            document.getElementById('submit-button').style.display ="none";

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
            if(selectedConstituency.options[selectedConstituency.selectedIndex].value){
                document.getElementById('vote-form').style.display = "block";
                document.getElementById('submit-button').style.display= "block";
            }
            else{
                document.getElementById('vote-form').style.display = "none";
                document.getElementById('submit-button').style.display ="none";
            }

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
