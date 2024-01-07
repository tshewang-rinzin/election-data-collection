import React from "react";
import ByConstituencies from "./charts/ByConstituencies";
import ByConstituenciesWithVoteType from "./charts/ByConstituenciesWithVoteType";

function ElectionResults() {
    return (
        <>
            <div className="row mb-3">
                <div className="col-sm-3">
                    <ConstituencyWinsChart />
                </div>
            </div>

            <div className="row">
                <div className="col-sm-12">
                    <ByConstituencies />
                </div>
                <div className="col-sm-12">
                    <ByConstituenciesWithVoteType />
                </div>
            </div>
        </>
    );
}

export default ElectionResults;
