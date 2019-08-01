import React, { Component } from 'react';

import { PaymentForm } from './PaymentForm';

export default class Booking extends Component {

    constructor(props) {
        super(props);

        let appState = JSON.parse(window.localStorage.getItem('appState'));

        this.state = {
            userData: appState.isLoggedIn ? appState.userData : [],
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            booking: [],
            totalPrice: 0,
            card: [],
            properties: [],
            success_message: '',
            error_message: ''
        };

        this.paymentSubmit = this.paymentSubmit.bind(this);

    }

    componentDidMount() {

        fetch(window.location.pathname + '/get_booking/', {
            method: 'GET',
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    booking: data.booking,
                    totalPrice: data.totalPrice,
                    card: data.card,
                    properties: data.properties
                });

            });

    }


    paymentSubmit(e) {

        const form = e.target;
        const data = new FormData(form);

        fetch('/booking/pay/', {
            method: 'POST',
            body: data,
            headers: {
                'X-CSRF-TOKEN': this.state.token
            },
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                if (data.status == 'error') {
                    this.setState({
                        error_message: data.message,
                        success_message: ''
                    });
                } else {
                    this.setState({
                        error_message: '',
                        success_message: data.message
                    });
                }

            });
    }


    render() {
        return (
            <div className="col-lg-10 col-lg-offset-1">

                <ul className="results-nav" style={{ marginTop: '20px', paddingBottom: '30px' }}>
                    <li>Results</li>
                    <li>Booking Details</li>
                    <li className="active">Payment</li>
                </ul>

                <div className="booking-details">

                    {this.state.success_message && (
                        <div className="alert alert-dismissible alert-success col-xs-12 payment-success">
                            <button type="button" className="close" data-dismiss="alert">×</button>
                            <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                            <p> {this.state.success_message} </p>
                        </div>
                    )}

                    {this.state.error_message && (
                        <div className="alert alert-dismissible alert-danger col-xs-12">
                            <button type="button" className="close" data-dismiss="alert">×</button>
                            <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                            <p> {this.state.error_message} </p>
                        </div>
                    )}

                    <h2 className="underline">Booking #{this.state.booking.id} </h2>

                    <div className="row">
                        <div className="col-lg-12">
                            <ul className="place-booking-properties">
                                {this.state.properties.map((property, i) => {
                                    return ( <li data-property={property.property_id} key={i}>
                                            {property.name}
                                            <input type="hidden" name="propertyId[]" value={property.property_id} />
                                        </li>
                                    );
                                })}
                            </ul>
                            <ul className="pull-right place-booking-properties" style={{ marginTop: '-10px' }}>
                                <li><strong>Total price:</strong> &pound;{this.state.totalPrice}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="row" style={{ marginTop: '20px' }}>
                        <div className="col-lg-6">
                            <p><strong>Booking details</strong></p>
                            <table className="table table-bookings">
                                <tbody>
                                <tr>
                                    <td>Adults:</td>
                                    <td>{this.state.booking.adults} </td>
                                </tr>
                                <tr>
                                    <td>Children:</td>
                                    <td>{this.state.booking.children} </td>
                                </tr>
                                <tr>
                                    <td>Infants:</td>
                                    <td>{this.state.booking.infants} </td>
                                </tr>
                                <tr>
                                    <td>Check in:</td>
                                    <td>{this.state.booking.start_date} </td>
                                </tr>
                                <tr>
                                    <td>Check out:</td>
                                    <td>{this.state.booking.end_date} </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div className="col-lg-6">
                            <p><strong>Customer details</strong></p>
                            <table className="table table-bookings">
                                <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td>{ this.state.userData.first_name } { this.state.userData.last_name } </td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{ this.state.userData.email } </td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>{ this.state.userData.phone } </td>
                                </tr>

                                {this.state.userData.company && (
                                    <tr>
                                        <td>Company:</td>
                                        <td>{ this.state.userData.company } </td>
                                    </tr>
                                )}

                                <tr>
                                    <td>Address:</td>
                                    <td>
                                        { this.state.userData.address } <br/>
                                        { this.state.userData.city } <br/>
                                        { this.state.userData.region } <br/>
                                        { this.state.userData.country } <br/>
                                        { this.state.userData.postcode }
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    {this.state.success_message || (!this.state.booking.paymentDate && this.state.booking.status == 0) && (
                        <PaymentForm card={this.state.card} booking={this.state.booking} paymentSubmit={this.paymentSubmit} />
                    )}

                    {this.state.success_message || this.state.booking.status !== 0 && (
                        <div className="row text-center">
                            <a href="/" className="btn btn-light">Return home</a>
                            <a href={ "profile/" + this.state.userData.id + "/bookings/" + this.state.booking.id + "/invoice" } className="btn btn-light">View
                                invoice</a>
                        </div>
                    )}

                </div>


            </div>

        );
    }

}
