import React, { Component } from 'react';

import { PropertyItem } from './PropertyItem';

export class SearchResults extends Component {

    constructor(props) {
        super(props);

        this.state = {
            chosenCottages: []
        };

        //this.placeBooking = this.placeBooking.bind(this);

    }


    //placeBooking() {
    //
    //    const form = document.getElementById('MagicCarpetSearchBar');
    //    const data = new FormData(form);
    //    data.append('productIDs', this.state.chosenCottages);
    //
    //    fetch('/add_to_booking', {
    //        method: 'POST',
    //        body: data,
    //    })
    //        .then(() => {
    //           // window.location.href = '/booking/place';
    //        });
    //}

    render() {

        const { properties } = this.props;

        return (
            <div className="results col-lg-8 col-lg-offset-2">

                {properties.length && (
                    <div>
                        <ul className="results-nav row">
                            <li className="active">Results</li>
                            <li>Booking Details</li>
                            <li>Payment</li>
                        </ul>

                        <div className="row">
                            <ul className="properties">
                                {properties.map((property, i) => {
                                    return <PropertyItem property={property} key={i} addToBooking={this.props.addToBooking}/>;
                                })}
                            </ul>
                        </div>
                        <div className="row">
                            <button className="btn btn-uppercourt place_booking pull-right" onClick={this.props.onSubmit} >Place booking</button>
                        </div>
                    </div>

                )}
                {!properties.length && (
                    <div className="row">
                        <div className="alert alert-danger no-properties" role="alert">No available properties for your
                            request. Try another dates or persons count.
                        </div>
                    </div>
                )}

            </div>
        );
    }

}
