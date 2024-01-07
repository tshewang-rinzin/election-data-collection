@extends('backend.layouts.app')

@section('title', __('Publish Constituency Result'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <h2>All Constituencies</h2>
                <ul class="list-group">
                    @foreach($allConstituencies as $constituencyItem)
                        <li class="list-group-item d-flex justify-content-between align-items-center {{ $constituencyItem->publish_result ? 'list-group-item-success' : 'list-group-item-danger' }}">
                            <div>
                                <span class="font-weight-bold">ID - {{ $constituencyItem->id }}</span> : <span class="font-weight-bold">{{ $constituencyItem->name }}</span> -
                                <span class="text-muted">
                                    {{ $constituencyItem->publish_result ? 'Published' : 'Not Published' }}
                                </span>
                                <br>
                                  @if ($constituencyItem->votes->count() > 0)
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Party</th>
                                                <th>Postal Ballot</th>
                                                <th>EVM</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($constituencyItem->votes as $vote)
                                                <tr>
                                                    <td>{{ $vote->party->name }}</td>
                                                    <td>{{ $vote->postal_ballot }}</td>
                                                    <td>{{ $vote->evm }}</td>
                                                    <td>{{ $vote->evm + $vote->postal_ballot }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    No votes yet.
                                @endif
                            </div>
                            @if ($constituencyItem->publish_result)
                                <a href="{{url('/dz/constituency-wise?constituency_id='.$constituencyItem->id)}}" class="btn btn-info btn-sm" target="_blank">
                                    Generate TV View
                                </a>
                            @elseif (!$constituencyItem->publish_result)
                                @if (
                                        $logged_in_user->hasAllAccess() ||
                                        (
                                            $logged_in_user->can('admin.access.election-result.publish')
                                        )
                                    )
                                <form method="post" action="{{ route('admin.election-result.publish', ['constituencyId' => $constituencyItem->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $constituencyItem->publish_result ? 'btn-danger' : 'btn-success' }}">
                                        Publish
                                    </button>
                                </form>
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')

@endpush
