// ByConstituencies.jsx
import React, { useState, useEffect } from "react";
// import { Bar } from "react-chartjs-2";
// import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

import {
    PieChart,
    Pie,
    Cell,
    Legend,
    Tooltip,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    ResponsiveContainer,
} from "recharts";
// import { Doughnut } from "react-chartjs-2";

// ChartJS.register(ArcElement, Tooltip, Legend);
import api from "../../../services/api"; // Adjust the path based on your file structure

function ConstituencyWinsChart() {
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState();

    // const partyColors = {
    //     "Bhutan Tendrel Party": "#31417d",
    //     "People's Democratic Party": "#89301e",
    //     // Add more party colors as needed
    // };

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get(
                    "/reports/count-constituency-wins"
                );
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

    console.log("data", dataValues);

    const processedData = dataValues.map((entry) => ({
        ...entry,
        fill: entry.color_code,
    }));

    const RADIAN = Math.PI / 180;
    const renderCustomizedLabel = ({
        cx,
        cy,
        midAngle,
        innerRadius,
        outerRadius,
        percent,
        index,
        name,
    }) => {
        const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
        const x = cx + radius * Math.cos(-midAngle * RADIAN);
        const y = cy + radius * Math.sin(-midAngle * RADIAN);

        return (
            <text
                x={x}
                y={y}
                fill="white"
                textAnchor={x > cx ? "start" : "end"}
                dominantBaseline="central"
            >
                {`${name}(${(percent * 100).toFixed(0)}%)`}
            </text>
        );
    };

    return (
        <div className="row justify-content-center">
            <div className="col-md-12 d-flex justify-content-center">
                <ResponsiveContainer width="50%" height={400}>
                    <PieChart>
                        <Pie
                            data={dataValues}
                            cx="50%"
                            cy="50%"
                            labelLine={false}
                            label={(props) =>
                                renderCustomizedLabel({
                                    ...props,
                                    name: data.partyWinsCount[props.index]
                                        .abbreviation,
                                })
                            }
                            fill="#8884d8"
                            dataKey="value"
                        >
                            {dataValues.map((entry, index) => (
                                <Cell
                                    key={`cell-${index}`}
                                    fill={dataValues[index].color_code}
                                />
                            ))}
                        </Pie>
                        <Tooltip />
                        {/* <Legend /> */}
                    </PieChart>
                </ResponsiveContainer>
                <ResponsiveContainer width={400} height={350}>
                    <BarChart data={processedData}>
                        {/* <CartesianGrid strokeDasharray="3 3" /> */}
                        <XAxis
                            dataKey="abbreviation"
                            tick={{ fill: "#fff" }}
                            axisLine={{ stroke: "#fff" }}
                            label={{
                                value: "Parties",
                                position: "insideBottom",
                                offset: -5,
                                fill: "#fff",
                            }}
                        />
                        <YAxis
                            label={{
                                value: "Number of Constituencies",
                                angle: -90,
                                fill: "#fff",
                                // position: "insideLeft",
                            }}
                            axisLine={{ stroke: "#fff" }}
                            tick={{ fill: "#fff" }}
                        />
                        <Tooltip />
                        <Bar
                            dataKey="value"
                            fill={(entry) => {
                                return entry.color_code;
                            }}
                        />
                    </BarChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}

export default ConstituencyWinsChart;
