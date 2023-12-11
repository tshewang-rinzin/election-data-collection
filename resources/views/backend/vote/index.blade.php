@extends('backend.layouts.app')

@section('title', __('Vote Management'))

{{-- @section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection --}}

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Vote Management')
        </x-slot>

        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.votes.create')"
                    :text="__('Add Votes')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.votes-table />
        </x-slot>
    </x-backend.card>
@endsection
