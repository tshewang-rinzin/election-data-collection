// ByConstituencies.jsx
import React, { useState, useEffect } from "react";
// import { Bar } from "react-chartjs-2";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

import { Doughnut } from "react-chartjs-2";

ChartJS.register(ArcElement, Tooltip, Legend);
import api from "../../../services/api"; // Adjust the path based on your file structure

function ConstituencyWinsChart() {
    const [chartData, setChartData] = useState({});
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState();

    const partyColors = {
        "Bhutan Tendrel Party": "#31417d",
        "People's Democratic Party": "#89301e",
        // Add more party colors as needed
    };

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get(
                    "/reports/get-wins-by-constituencies"
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

    const labels = Object.keys(data.partyWinsCount);
    const dataValues = Object.values(data.partyWinsCount);

    const datasets = [
        {
            data: dataValues,
            backgroundColor: labels.map((party) => partyColors[party]),
            borderColor: Object.values(partyColors),
            borderWidth: 1,
        },
    ];

    const finalChartData = {
        labels: labels,
        datasets: datasets,
    };

    return (
        <div>
            <h2>Constituencies Won by Each Party</h2>

            <Doughnut
                data={finalChartData}
                options={{
                    cutout: 0,
                    responsive: true,
                    maintainAspectRatio: true,
                    // width: 200, // Adjust the width as needed
                    // height: 200, // Adjust the height as needed
                    // maintainAspectRatio: false, // You can adjust this based on your preference
                }}
            />
        </div>
    );
}

export default ConstituencyWinsChart;
