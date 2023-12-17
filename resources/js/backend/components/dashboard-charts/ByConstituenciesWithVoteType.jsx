import React, { useState, useEffect } from "react";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";

import { Bar } from "react-chartjs-2";
import api from "../../../services/api";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError(error) {
        return { hasError: true };
    }

    componentDidCatch(error, errorInfo) {
        logErrorToMyService(error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            return <div>Something went wrong.</div>;
        }

        return this.props.children;
    }
}

function logErrorToMyService(error, errorInfo) {
    // You can log errors to an error reporting service here
    console.error("Error in chart component:", error, errorInfo);
}

function ByConstituenciesWithVoteType() {
    const [chartData, setChartData] = useState({});
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState();

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get(
                    "/reports/by-constituencies-with-vote-type"
                );
                setChartData(response.chartData);
                setData(response);
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    const options = {
        indexAxis: "x", // This makes the bar chart horizontal
        scales: {
            x: {
                // stacked: true,
                beginAtZero: true,
            },
            y: {
                beginAtZero: true,
                // maxBarThickness: 50,
            },
        },
        // elements: {
        //     bar: {
        //         barThickness: 12000, // Adjust this value as needed
        //         maxBarThickness: 12000, // Adjust this value as needed
        //     },
        // },
        barPercentage: 0.8, // Adjust as needed
        barThickness: "flex",
        plugins: {
            legend: {
                position: "top",
            },
        },
    };

    const constituencyLabels = Object.values(data.chartData).map(
        (data) => data.label
    );
    const partyColors = data.parties.map(
        (party) => party.color_code || "#000000"
    );

    const datasets = data.parties.flatMap((party, index) => {
        const partyDataArray = Object.values(chartData).map((data) => {
            const partyData = data.data.find((d) => d.label === party.name);
            const evm = partyData ? parseInt(partyData.evm, 10) : 0;
            const postalBallot = partyData
                ? parseInt(partyData.postal_ballot, 10)
                : 0;
            return [evm, postalBallot];
        });

        const partyColor = partyColors[index];
        const postalBallotColor = lightenColor(partyColor, 0.3);

        return [
            {
                label: `${party.name} - EVM`,
                data: partyDataArray.map(([evm]) => evm),
                backgroundColor: partyColor,
                borderColor: partyColor,
                borderWidth: 1,
            },
            {
                label: `${party.name} - Postal Ballot`,
                data: partyDataArray.map(([, postalBallot]) => postalBallot),
                backgroundColor: postalBallotColor,
                borderColor: postalBallotColor,
                borderWidth: 1,
            },
        ];
    });

    function lightenColor(color, factor) {
        // Parse the color into RGB components
        const hex = color.slice(1); // Remove the '#' at the beginning
        const bigint = parseInt(hex, 16);
        const r = (bigint >> 16) & 255;
        const g = (bigint >> 8) & 255;
        const b = bigint & 255;

        // Calculate the new RGB values with increased brightness
        const newR = Math.min(255, r + (255 - r) * factor);
        const newG = Math.min(255, g + (255 - g) * factor);
        const newB = Math.min(255, b + (255 - b) * factor);

        // Convert the new RGB values back to hex
        const newColor = `#${Math.round(newR).toString(16)}${Math.round(
            newG
        ).toString(16)}${Math.round(newB).toString(16)}`;

        return newColor;
    }

    function colorToRGBA(color) {
        const canvas = document.createElement("canvas");
        canvas.width = canvas.height = 1;
        const ctx = canvas.getContext("2d");
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, 1, 1);
        return ctx.getImageData(0, 0, 1, 1).data;
    }

    const finalChartData = {
        labels: constituencyLabels,
        datasets: datasets,
    };

    return (
        <ErrorBoundary>
            <div>
                <h1>
                    Vote Distribution by Constituencies (EVM and Postal Ballot)
                </h1>

                <Bar
                    data={finalChartData}
                    options={options}
                    // width={100}
                    // height={50}
                    // options={{ maintainAspectRatio: false }}
                />
            </div>
        </ErrorBoundary>
    );
}

export default ByConstituenciesWithVoteType;
