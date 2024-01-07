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
    LabelList,
} from "recharts";

import { lighten } from "polished";
// import { Doughnut } from "react-chartjs-2";

// ChartJS.register(ArcElement, Tooltip, Legend);
import api from "../../../services/api"; // Adjust the path based on your file structure

const CustomBar = (props) => {
    const { fill, x, y, width, height } = props;

    // Define the depth of the 3D effect
    const depth = 10;

    // Create a shadow effect for the 3D effect
    const shadow = (
        <rect
            x={x + width}
            y={y}
            width={depth}
            height={height}
            fill={lighten(0.1, fill)}
        />
    );

    return (
        <>
            <rect {...props} />
            {shadow}
        </>
    );
};

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
    const renderCustomizedLabelForBar = (props, legend) => {
        const { x, y, width, height, value, position } = props;
        const radius = 10;

        return (
            <text
                x={x + width / 2}
                y={y + height + radius + 10} // Adjust the y-coordinate to place it at the bottom
                fill="#fff"
                textAnchor="middle"
                dominantBaseline="middle"
                style={{ fontSize: "20px", fontFamily: "Oswald-Bold" }}
                // transform={`rotate(-90 ${x + width / 2} ${
                //     y + height + radius + 10
                // })`} // Rotate the text by -90 degrees
            >
                <tspan
                    x={x + width / 2}
                    dy="-2.5em"
                    fill="#fff"
                    style={{
                        fontFamily: "Wangdi29",
                    }}
                >
                    {legend}
                </tspan>
            </text>
        );
    };

    return (
        <div className="row justify-content-center">
            <div
                className="col-md-12 text-center no-of-seats"
                style={{
                    fontFamily: "Oswald-Bold",
                    fontSize: "20px",
                    marginLeft: "80px",
                }}
            >
                NUMBER OF SEATS
            </div>
            <div className="col-md-12 d-flex justify-content-center">
                <ResponsiveContainer width={400} height={350}>
                    <BarChart
                        data={processedData}
                        margin={{
                            top: 30,
                            right: 10,
                            left: 10,
                            bottom: 5,
                        }}
                    >
                        {/* <CartesianGrid strokeDasharray="3 3" /> */}
                        <XAxis
                            dataKey="abbreviation"
                            tick={{ fill: "#fff" }}
                            axisLine={{ stroke: "#fff" }}
                            // label={{
                            //     value: "Parties",
                            //     position: "insideBottom",
                            //     offset: -5,
                            //     fill: "#fff",
                            // }}
                        />
                        <YAxis
                            domain={[0, "dataMax + 1"]}
                            // label={{
                            //     value: "Number of Constituencies",
                            //     angle: -90,
                            //     fill: "#fff",
                            //     // position: "insideLeft",
                            // }}
                            axisLine={{ stroke: "#fff" }}
                            tick={false}
                        />
                        {/* <Tooltip /> */}
                        <Bar
                            dataKey="value"
                            shape={<CustomBar />}
                            fill={(entry) => {
                                return entry.color_code;
                            }}
                        >
                            <LabelList
                                dataKey="value"
                                position="top"
                                // angle={-90}
                                fill="#fff"
                                // content={({ value }) => `TOTAL: ${value}`}
                            />
                        </Bar>
                    </BarChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}

export default ConstituencyWinsChart;
