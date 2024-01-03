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

const CustomPieSlice = (props) => {
    const { cx, cy, innerRadius, outerRadius, startAngle, endAngle, fill } =
        props;

    // Define the depth of the 3D effect
    const depth = 15;

    // Calculate the start and end points for the 3D effect
    const startInnerX = cx + innerRadius * Math.cos(-startAngle * RADIAN);
    const startInnerY = cy + innerRadius * Math.sin(-startAngle * RADIAN);
    const startOuterX = cx + outerRadius * Math.cos(-startAngle * RADIAN);
    const startOuterY = cy + outerRadius * Math.sin(-startAngle * RADIAN);

    const endInnerX = cx + innerRadius * Math.cos(-endAngle * RADIAN);
    const endInnerY = cy + innerRadius * Math.sin(-endAngle * RADIAN);
    const endOuterX = cx + outerRadius * Math.cos(-endAngle * RADIAN);
    const endOuterY = cy + outerRadius * Math.sin(-endAngle * RADIAN);

    // Calculate the control points for the 3D effect
    const controlInnerX =
        cx + (innerRadius + depth) * Math.cos(-startAngle * RADIAN);
    const controlInnerY =
        cy + (innerRadius + depth) * Math.sin(-startAngle * RADIAN);
    const controlOuterX =
        cx + (outerRadius + depth) * Math.cos(-startAngle * RADIAN);
    const controlOuterY =
        cy + (outerRadius + depth) * Math.sin(-startAngle * RADIAN);

    return (
        <>
            <path
                {...props}
                d={`
                    M${startInnerX},${startInnerY}
                    L${startOuterX},${startOuterY}
                    Q${controlOuterX},${controlOuterY}
                    ${endOuterX},${endOuterY}
                    L${endInnerX},${endInnerY}
                    Q${controlInnerX},${controlInnerY}
                    ${startInnerX},${startInnerY}
                `}
                fill={fill}
            />
        </>
    );
};

// Custom Doughnut Pie Slice
const CustomDoughnutPieSlice = (props) => {
    const { cx, cy, innerRadius, outerRadius, startAngle, endAngle, fill } =
        props;

    // Define the depth of the doughnut hole
    const holeRadius = 60;

    // Calculate the start and end points
    const startInnerX = cx + innerRadius * Math.cos(-startAngle * RADIAN);
    const startInnerY = cy + innerRadius * Math.sin(-startAngle * RADIAN);
    const startOuterX = cx + outerRadius * Math.cos(-startAngle * RADIAN);
    const startOuterY = cy + outerRadius * Math.sin(-startAngle * RADIAN);

    const endInnerX = cx + innerRadius * Math.cos(-endAngle * RADIAN);
    const endInnerY = cy + innerRadius * Math.sin(-endAngle * RADIAN);
    const endOuterX = cx + outerRadius * Math.cos(-endAngle * RADIAN);
    const endOuterY = cy + outerRadius * Math.sin(-endAngle * RADIAN);

    // Calculate the control points for the doughnut hole
    const controlInnerX = cx + holeRadius * Math.cos(-startAngle * RADIAN);
    const controlInnerY = cy + holeRadius * Math.sin(-startAngle * RADIAN);
    const controlOuterX = cx + holeRadius * Math.cos(-startAngle * RADIAN);
    const controlOuterY = cy + holeRadius * Math.sin(-startAngle * RADIAN);

    return (
        <>
            <path
                {...props}
                d={`
                    M${startInnerX},${startInnerY}
                    L${startOuterX},${startOuterY}
                    Q${controlOuterX},${controlOuterY}
                    ${endOuterX},${endOuterY}
                    L${endInnerX},${endInnerY}
                    Q${controlInnerX},${controlInnerY}
                    ${startInnerX},${startInnerY}
                `}
                fill={fill}
            />
        </>
    );
};

const OverallResult = () => {
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState();

    // const partyColors = {
    //     "Bhutan Tendrel Party": "#31417d",
    //     "People's Democratic Party": "#89301e",
    //     // Add more party colors as needed
    // };

    useEffect(() => {
        const getData = async () => {
            try {
                const response = await api.get(
                    "/reports/count-constituency-wins"
                );
                console.log("response123", response);
                setData(response.partyWinsCount);
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                setLoading(false);
            }
        };

        getData();
    }, []);

    return (
        <div className="card overall-result-container">
            <div className="card-header bg-success text-white pb-1">
                <div className="total_label">འདེམས་ཁྲི་ཡོངས་བསྡོམས།</div>
            </div>
            <div className="card-body p-0">
                {loading ? (
                    <p>Loading...</p>
                ) : (
                    <table className="table table-bordered table-striped mb-0">
                        <tbody>
                            {data &&
                                data.map((party) => (
                                    <tr
                                        key={party.abbreviation}
                                        style={{
                                            backgroundColor: party.color_code,
                                        }}
                                    >
                                        <td className="text-white text-left p-1">
                                            <div className="partylogo">
                                                <img src={party.logo} />
                                            </div>
                                        </td>
                                        <td className="p-1 text-white text-left ">
                                            <div className="party_labels">
                                                {party.dz_name}
                                            </div>
                                        </td>
                                        <td className="p-1 text-white  text-center">
                                            <div className="party_labels_value">
                                                {party.value}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                    </table>
                )}
            </div>
        </div>
    );
};

function ConstituencyWiseChartDzongkha() {
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
        dz_name: partyVotes[partyName].dz_name,
        total: partyVotes[partyName].total_votes,
        evm: partyVotes[partyName].evm_votes,
        pb: partyVotes[partyName].postal_ballot_votes,
        color_code: partyVotes[partyName].color_code,
        abbreviation: partyVotes[partyName].abbreviation,
        evmColor: partyVotes[partyName].color_code,
        // Add more properties as needed
        partyId: partyVotes[partyName].party_id,
        logo: partyVotes[partyName].logo,
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
        <div className="dz">
            <div className="constituency-chart-container">
                <img
                    src="/img/CORNER.png"
                    className="constituency-chart-corner-img"
                />

                <div className="constituency-chart-main-container">
                    <div className="constituency-wise-header">
                        <h2 className="text-center">
                            རྒྱལ་ཡོངས་ཚོགས་འསྡུའི་བཙག་འཐུ། ༢༠༢༣-༢༠༢༤
                        </h2>
                        <h1 className="text-center">
                            སྤྱིར་བཏང་བཙག་འཐུའི་ གནས་སྐབས་ཀྱི་གྲུབ་འབྲས།
                        </h1>
                        <div className="text-center mb-0 constituency-info">
                            <div>
                                {constituency.dz_name}{" "}
                                {constituency.dzongkhag.dz_name}
                            </div>
                        </div>
                    </div>

                    <div
                        id="chartContainer"
                        className="row justify-content-center mt-1 "
                    >
                        <div className="constituency-chart-flex-container">
                            <div
                                className="constituency-chart-frame-container"
                                id="frameContainer"
                            >
                                {partyData &&
                                    partyData.map((item) => (
                                        <div className="constituency-chart-frame">
                                            <div className="party_logo">
                                                <img src={item.logo} />
                                            </div>
                                            <div className="party_name">
                                                {item.dz_name}
                                                {/* བྷུ་ཊཱན་རྟེན་འབྲེལ་ཚོགས་པ། */}
                                            </div>
                                            <div className="candidate_img">
                                                {/* Find the candidate image for the respective party */}
                                                {constituency.candidates.map(
                                                    (candidate) => {
                                                        if (
                                                            candidate.party_id ===
                                                            item.partyId
                                                        ) {
                                                            return (
                                                                <>
                                                                    <img
                                                                        key={
                                                                            candidate.id
                                                                        }
                                                                        src={`/storage/${candidate.profile_image}`}
                                                                        alt={`/storage/${candidate.name}'s Image`}
                                                                    />

                                                                    <div className="candidate-name">
                                                                        {
                                                                            candidate.dz_name
                                                                        }
                                                                    </div>
                                                                </>
                                                            );
                                                        }
                                                        return null;
                                                    }
                                                )}
                                            </div>

                                            <hr
                                                color="white"
                                                style={{
                                                    marginTop: "0px",
                                                    marginBottom: "5px",
                                                }}
                                            />
                                            <div className="votes-container">
                                                <div className="vote-row">
                                                    <div className="vote-label">
                                                        འགྲེམ་ཐོག
                                                    </div>
                                                    <div className="vote-value">
                                                        {item.pb}
                                                    </div>
                                                </div>
                                                <div className="vote-row">
                                                    <div className="vote-label">
                                                        འཕྲུལ་ཆས།
                                                    </div>
                                                    <div className="vote-value">
                                                        {item.evm}
                                                    </div>
                                                </div>
                                                <div className="vote-row">
                                                    <div className="vote-label grand-total">
                                                        ཡོངས་བསྡོམས།
                                                    </div>
                                                    <div className="vote-value grand-total">
                                                        {item.total}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                            </div>
                            <div
                                className="constituency-chart-chart-grid-1"
                                id="chartGrid"
                                // style={{ width: "400px", marginRight: "20px" }}
                            >
                                <div className="bar-chart-container">
                                    <ResponsiveContainer width={400}>
                                        <BarChart
                                            data={partyData}
                                            margin={{
                                                top: 5,
                                                right: 10,
                                                left: 10,
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
                                            {/* <Tooltip /> */}
                                            <Bar
                                                activeBar={false}
                                                dataKey="evm"
                                                shape={<CustomBar />}
                                            >
                                                {partyData.map(
                                                    (entry, index) => (
                                                        <Cell
                                                            key={`cell-${index}`}
                                                            fill={lighten(
                                                                0.2,
                                                                entry.color_code
                                                            )}
                                                        />
                                                    )
                                                )}
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
                                            <Bar
                                                activeBar={false}
                                                dataKey="pb"
                                                shape={<CustomBar />}
                                            >
                                                {partyData.map(
                                                    (entry, index) => (
                                                        <Cell
                                                            key={`cell-${index}`}
                                                            fill={lighten(
                                                                0.1,
                                                                entry.color_code
                                                            )}
                                                        />
                                                    )
                                                )}
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
                                                        renderCustomizedLabelForBar(
                                                            props,
                                                            "PB"
                                                        )
                                                    }
                                                />
                                            </Bar>
                                            <Bar
                                                activeBar={false}
                                                dataKey="total"
                                                shape={<CustomBar />}
                                            >
                                                {partyData.map(
                                                    (entry, index) => (
                                                        <Cell
                                                            key={`cell-${index}`}
                                                            fill={
                                                                entry.color_code
                                                            }
                                                        />
                                                    )
                                                )}
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
                                </div>
                                <div className="result-container">
                                    <OverallResult />
                                </div>
                            </div>
                            {/* <div
                                className="constituency-chart-chart-grid-2"
                                id="chartGrid"
                                // style={{ width: "400px" }}
                            > */}
                            {/* <ResponsiveContainer width={250}>
                                    <PieChart>
                                        <Pie
                                            data={partyData}
                                            cx="50%"
                                            cy="70%"
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
                                            shape={<CustomDoughnutPieSlice />}
                                        >
                                            {partyData.map((entry, index) => (
                                                <Cell
                                                    key={`cell-${index}`}
                                                    fill={entry.color_code}
                                                />
                                            ))}
                                        </Pie>
                                    </PieChart>
                                </ResponsiveContainer> */}
                            {/* </div> */}
                        </div>
                    </div>
                </div>
                <img
                    className="constituency-chart-corner-img-right"
                    src="/img/CORNER.png"
                    // style={{
                    //     position: "absolute",
                    //     width: 60,
                    //     right: 10,
                    //     bottom: 10,
                    //     transform: "rotate(180deg)",
                    // }}
                />
            </div>
        </div>
    );
}

export default ConstituencyWiseChartDzongkha;
