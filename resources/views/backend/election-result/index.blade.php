@extends('backend.layouts.app')

@section('title', __('Election Result'))

@section('content')

    <div>
        <div id="root"></div>
        {{-- <div class="row">
            <div class="col-sm-6">

                <canvas id="stacked-bar-chart" width="200px" height="200px" aria-label="Constituency Wise" role="img"></canvas>
            </div>
            <div class="col-sm-6">

                <canvas id="stacked-bar-char2" width="200" height="200" aria-label="Constituency Wise" role="img"></canvas>
            </div>
        </div> --}}
    </div>

@endsection

@push(
    'after-scripts'
)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

// document.addEventListener('DOMContentLoaded', function () {
//     // Fetch your data from the backend
//     const chartData = @json($chartData);
//     const parties = @json($parties);

//     console.log('chartData:', chartData);

//     const ctx = document.getElementById('stacked-bar-chart').getContext('2d');

//     const datasets = parties.map((party, index) => {
//         return {
//             label: party.name,
//             data: Object.values(chartData).map(item => item.data[index] || 0), // Use 0 if data is undefined
//             backgroundColor: party.color || getRandomColor(),
//         };
//     });

//     const stackedBarChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: Object.values(chartData).map(item => item.label),
//             datasets: datasets,
//         },
//         options: {
//             scales: {
//                 x: {
//                     stacked: true,
//                 },
//                 y: {
//                     stacked: true,
//                 },
//             },
//         },
//     });

//     // Function to generate a random color
//     function getRandomColor() {
//         const letters = '0123456789ABCDEF';
//         let color = '#';
//         for (let i = 0; i < 6; i++) {
//             color += letters[Math.floor(Math.random() * 16)];
//         }
//         return color;
//     }
// });



</script>

@endpush
