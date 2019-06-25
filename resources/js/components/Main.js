import React, { Component } from 'react';

import { Form } from './Form';
import { SearchResults } from './SearchResults';
import { BookingItem } from './booking/BookingItem';
import { BookingForm } from './booking/BookingForm';

export default class Main extends Component {

    constructor(props) {
        super(props);

        this.state = {
            chosenCottages: [],
            proceedBooking: false,
            formData: {
                cottage: 'all',
                holiday_type: 0,
                checkin: "",
                checkout: "",
                adults: 0,
                children: 0,
                infants: 0
            },
            userData: []
        };

        this.onFormSubmit = this.onFormSubmit.bind(this);
        this.placeBooking = this.placeBooking.bind(this);
        this.addToBooking = this.addToBooking.bind(this);

    }

    componentDidMount() {

        let appState = JSON.parse(window.localStorage.getItem('appState'));

        if (appState.isLoggedIn) {

            this.setState({
                userData: appState.userData
            });

        }
    }

    onFormSubmit(e) {
        e.preventDefault();

        if (!event.target.checkValidity()) {
            this.setState({
                invalid: true,
                displayErrors: true
            });
            return;
        }
        const form = e.target;
        const data = new FormData(form);

        fetch('/search', {
            method: 'POST',
            body: data,
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    properties: data.propertiesObj
                });

            });


        // TODO find another way to save form details
        this.setState({
            formData: {
                cottage: 'all',
                holiday_type: document.getElementById('holiday_type').value,
                checkin: document.getElementById('checkin_input').value,
                checkout: document.getElementById('checkout_input').value,
                adults: document.getElementById('adults').value,
                children: document.getElementById('children').value,
                infants: document.getElementById('infants').value
            }
        });
    }

    placeBooking() {

        this.setState({
            proceedBooking: true
        });

    }

    addToBooking(property_id) {

        let remove = this.state.chosenCottages.indexOf(property_id);

        if (remove == -1) {
            this.setState({
                chosenCottages: [...this.state.chosenCottages, property_id]
            });
        } else {
            this.setState({
                chosenCottages: this.state.chosenCottages.filter((_, i) => i !== remove)
            });
        }

    }


    render() {

        return (
            <div className="row">
                {this.state.proceedBooking && (
                    <div className="col-lg-10 col-lg-offset-1" style={{marginTop: '30px'}}>

                        <div className="col-lg-12">
                            <ul className="results-nav row">
                                <li>Results</li>
                                <li className="active">Booking Details</li>
                                <li>Payment</li>
                            </ul>
                        </div>

                        <div className="row">
                            <div className="col-lg-12 place-booking">

                                <h2 className="underline">Your booking</h2>

                                <BookingItem properties={this.state.chosenCottages} />
                                <Form onSubmit={this.onFormSubmit} proceedBooking={this.state.proceedBooking}  formData={this.state.formData} />
                                <BookingForm />
                             </div>
                        </div>
                    </div>
                )}

                {!this.state.proceedBooking && (
                    <div className="row">
                        <div className="col-lg-8 col-lg-offset-2 booking-availability" style={{marginTop: '35px'}}>
                            <Form onSubmit={this.onFormSubmit} formData={this.state.formData} />
                        </div>
                        {this.state.properties && (
                            <SearchResults properties={this.state.properties} onSubmit={this.placeBooking}
                                           addToBooking={this.addToBooking}/>
                        )}
                    </div>
                )}
            </div>
        );
    }
}
