import React from "react";
import ByConstituencies from "./dashboard-charts/ByConstituencies";
import ByConstituenciesWithVoteType from "./dashboard-charts/ByConstituenciesWithVoteType";
import ConstituencyWinsChart from "./dashboard-charts/ConstituencyWinsChart";

function Dashboard() {
    return (
        <>
            <div className="row mb-3">
                <div className="col-md-12">
                    <h2 className="text-center" style={{ color: "white" }}>
                        PROVISIONAL RESULT
                    </h2>
                    <h3
                        className="text-center white"
                        style={{ color: "white" }}
                    >
                        NA GENERAL ELECTION
                    </h3>
                    <hr color="white" />
                    <ConstituencyWinsChart />
                </div>
            </div>

            {/* <div className="row">
                <div className="col-sm-12">
                    <ByConstituencies />
                </div>
                <div className="col-sm-12">
                    <ByConstituenciesWithVoteType />
                </div>
            </div> */}
        </>
    );
}

export default Dashboard;
