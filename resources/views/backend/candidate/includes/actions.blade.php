
@if ($logged_in_user->hasAllAccess())
    {{-- <x-utils.view-button :href="route('admin.votes.show', $user)" /> --}}
    <x-utils.edit-button :href="route('admin.candidates.create', ['constituency_id'=>$candidate->constituency_id])" />
@endif

@if ( $logged_in_user->hasAllAccess() && $logged_in_user->isMasterAdmin())
    <x-utils.delete-button :href="route('admin.candidates.destroy', $candidate)" />
@endif
