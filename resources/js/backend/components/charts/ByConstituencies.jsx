// ByConstituencies.jsx
import React, { useState, useEffect } from "react";
import "chart.js/auto";
import { Bar } from "react-chartjs-2";
import api from "../../../services/api"; // Adjust the path based on your file structure

function ByConstituencies() {
    const [chartData, setChartData] = useState({});
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState();

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get("/reports/by-constituencies");
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
        scales: {
            y: {
                type: "linear",
                beginAtZero: true,
            },
        },
    };

    const constituencyLabels = Object.values(data.chartData).map(
        (data) => data.label
    );
    const partyColors = data.parties.map(
        (party) => party.color_code || "#000000"
    ); // Use party color or default to black

    const datasets = data.parties.map((party, index) => ({
        label: party.name,
        data: Object.values(data.chartData).map((data) => data.data[index]),
        backgroundColor: partyColors[index],
        borderColor: partyColors[index],
        borderWidth: 1,
    }));

    const finalChartData = {
        labels: constituencyLabels,
        datasets: datasets,
    };

    return (
        <div>
            <h1>Vote Distribution by Constituencies</h1>
            <Bar data={finalChartData} options={options} />
        </div>
    );
}

export default ByConstituencies;
