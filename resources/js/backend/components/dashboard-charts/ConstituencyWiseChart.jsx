import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import {
    PieChart,
    Pie,
    Cell,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    ResponsiveContainer,
    Tooltip,
    LabelList,
} from "recharts";
import api from "../../../services/api"; // Adjust the path based on your file structure

import { lighten } from "polished";

function ConstituencyWiseChart() {
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState(null);
    const location = useLocation();
    const queryParams = new URLSearchParams(location.search);
    const constituency_id = queryParams.get("constituency_id");

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get(
                    `/reports/constituency-wise?constituency_id=${constituency_id}`
                );
                console.log("response", response);
                setData(response); // Assuming the data is in the 'data' property of the response
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [constituency_id]);

    if (loading) {
        return <div>Loading...</div>;
    }

    const partyVotes = data.partyVotes;
    const constituency = data.constituency;

    const colorMapping = {
        BTP: ["#3489E5", "#4CA1AF", "#91C7B1"], // Colors for BTP (EVM, PB, Total)
        PDP: ["#6e65c5", "#6E75A4", "#7873A5"], // Colors for PDP (EVM, PB, Total)
        // Add more parties as needed
    };

    const partyData = Object.keys(partyVotes).map((partyName) => ({
        name: partyName,
        total: partyVotes[partyName].total_votes,
        evm: partyVotes[partyName].evm_votes,
        pb: partyVotes[partyName].postal_ballot_votes,
        color_code: partyVotes[partyName].color_code,
        abbreviation: partyVotes[partyName].abbreviation,
        evmColor: partyVotes[partyName].color_code,
        // Add more properties as needed
    }));

    console.log("partyData", partyData);

    const RADIAN = Math.PI / 180;

    const renderLabel = () => {
        return <text>Tshewang</text>;
    };

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
                style={{ fontSize: "12px" }}
                // transform={`rotate(-90 ${x + width / 2} ${
                //     y + height + radius + 10
                // })`} // Rotate the text by -90 degrees
            >
                <tspan x={x + width / 2} dy="-2.5em" fill="#fff">
                    {legend}
                </tspan>
            </text>
        );
    };

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
    const colors = ["#0088FE", "#00C49F", "#FFBB28", "#FF8042", "red", "pink"];

    return (
        <div>
            <div
                className="mt-5 mb-3 pt-5 text-center"
                style={{ color: "white" }}
            >
                <h2
                    className="text-center mt-5"
                    style={{
                        fontFamily: "Oswald-Light",
                        color: "#ffde00",
                        fontSize: "30px",
                    }}
                >
                    National Assembly Elections 2023-2024
                </h2>
                <h1
                    className="text-center"
                    style={{
                        fontFamily: "Oswald-Light",
                        fontSize: "40px",
                    }}
                >
                    GENERAL ROUND PROVISIONAL RESULTS
                </h1>
                <div
                    className="text-center mb-5"
                    style={{
                        fontFamily: "Oswald-Light",
                        color: "#ffde00",
                        fontSize: "30px",
                    }}
                >
                    {constituency.dzongkhag.name} ({constituency.name})
                </div>
            </div>
            <div className="row justify-content-center mt-5">
                <div className="col-md-12 d-flex justify-content-center">
                    <img
                        src="/img/CORNER.png"
                        style={{
                            position: "absolute",
                            width: 80,
                            left: -100,
                            top: -150,
                        }}
                    />
                    <img src="/img/FRAME1.PNG" height={400} />
                    <img src="/img/FRAME2.PNG" height={400} />

                    <ResponsiveContainer width={500} height={400}>
                        <BarChart
                            data={partyData}
                            margin={{
                                top: 5,
                                right: 30,
                                left: 20,
                                bottom: 5,
                            }}
                        >
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
                                domain={[0, "dataMax + 100"]}
                                axisLine={{ stroke: "#fff" }}
                                tick={false}
                                // width={100} // Adjust the width as needed
                                // interval={0}
                            />
                            <Tooltip />
                            <Bar dataKey="evm">
                                {partyData.map((entry, index) => (
                                    <Cell
                                        key={`cell-${index}`}
                                        fill={lighten(0.2, entry.color_code)}
                                    />
                                ))}
                                <LabelList
                                    dataKey="evm"
                                    position="top"
                                    // angle={-90}
                                    fill="#fff"
                                    // content={({ value }) => `TOTAL: ${value}`}
                                />
                                <LabelList
                                    dataKey="evm"
                                    position="inside"
                                    // angle={-90}
                                    fill="#fff"
                                    content={(props) =>
                                        renderCustomizedLabelForBar(
                                            props,
                                            "EVM"
                                        )
                                    }
                                />
                            </Bar>
                            <Bar dataKey="pb">
                                {partyData.map((entry, index) => (
                                    <Cell
                                        key={`cell-${index}`}
                                        fill={lighten(0.1, entry.color_code)}
                                    />
                                ))}
                                <LabelList
                                    dataKey="pb"
                                    position="top"
                                    // angle={-90}
                                    fill="#fff"
                                    // content={({ value }) => `TOTAL: ${value}`}
                                />
                                <LabelList
                                    // dataKey="pb"
                                    position="middle"
                                    fill="#fff"
                                    content="PB"
                                    content={(props) =>
                                        renderCustomizedLabelForBar(props, "PB")
                                    }
                                />
                            </Bar>
                            <Bar dataKey="total">
                                {partyData.map((entry, index) => (
                                    <Cell
                                        key={`cell-${index}`}
                                        fill={entry.color_code}
                                    />
                                ))}
                                <LabelList
                                    dataKey="total"
                                    position="top"
                                    // angle={-90}
                                    fill="#fff"
                                    // content={({ value }) => `TOTAL: ${value}`}
                                />
                                <LabelList
                                    // dataKey="total"
                                    position="inside"
                                    fill="#fff"
                                    content={(props) =>
                                        renderCustomizedLabelForBar(
                                            props,
                                            "TOTAL"
                                        )
                                    }
                                />
                            </Bar>
                        </BarChart>
                    </ResponsiveContainer>
                    <ResponsiveContainer width={500} height={400}>
                        <PieChart>
                            <Pie
                                data={partyData}
                                cx="50%"
                                cy="50%"
                                labelLine={false}
                                labelLine={false}
                                label={(props) =>
                                    renderCustomizedLabel({
                                        ...props,
                                        name: partyData[props.index]
                                            .abbreviation,
                                    })
                                }
                                fill="#8884d8"
                                dataKey="total"
                            >
                                {partyData.map((entry, index) => (
                                    <Cell
                                        key={`cell-${index}`}
                                        fill={entry.color_code}
                                    />
                                ))}
                            </Pie>
                            <Tooltip />
                        </PieChart>
                    </ResponsiveContainer>
                    <img
                        src="/img/CORNER.png"
                        style={{
                            position: "absolute",
                            width: 80,
                            right: -100,
                            bottom: -150,
                            transform: "rotate(180deg)",
                        }}
                    />
                </div>
            </div>
        </div>
    );
}

export default ConstituencyWiseChart;
