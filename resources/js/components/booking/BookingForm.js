import React, { Component } from 'react';
import SimpleReactValidator from 'simple-react-validator';

export class BookingForm extends Component {

    constructor(props) {
        super(props);


        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            email: this.props.userData ? this.props.userData.email : '',
            title: this.props.userData ? this.props.userData.title : '',
            first_name: this.props.userData ? this.props.userData.first_name : '',
            last_name: this.props.userData ? this.props.userData.last_name : '',
            company: this.props.userData ? this.props.userData.company : '',
            address: this.props.userData ? this.props.userData.address : '',
            city: this.props.userData ? this.props.userData.city : '',
            postcode: this.props.userData ? this.props.userData.postcode : '',
            region: this.props.userData ? this.props.userData.region : '',
            country: this.props.userData ? this.props.userData.country : '',
            phone: this.props.userData ? this.props.userData.phone : '',
            notes: ''
        };

        this.validator = new SimpleReactValidator();

        this.handleUserInput = this.handleUserInput.bind(this);
        this.bookingFormSubmit = this.bookingFormSubmit.bind(this);

    }

    handleUserInput(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({
            [name]: value
        });
    }

    bookingFormSubmit(e) {
        e.preventDefault();

        if (this.validator.allValid()) {


            let propertyInputs = document.getElementsByName('propertyId[]');
            let propertyIds = [];

            for (let i = 0; i < propertyInputs.length; i++) {
                propertyIds.push(propertyInputs[i].value);
            }

            let data = {
                _method : 'PATCH',
                _token: this.state.token,
                email: this.state.email,
                title: this.state.title,
                first_name: this.state.first_name,
                last_name: this.state.last_name,
                company: this.state.company,
                address: this.state.address,
                city: this.state.city,
                postcode: this.state.postcode,
                region: this.state.region,
                country: this.state.country,
                phone: this.state.phone,
                property_ids : propertyIds,
                start_date: document.getElementById('checkin_input').value,
                end_date: document.getElementById('checkout_input').value,
                adults: document.getElementById('adults').value,
                children: document.getElementById('children').value,
                infants: document.getElementById('infants').value,
                notes: this.state.notes,
                holiday_type: document.getElementById('holiday_type').value
            };

            fetch('/booking/save', {
                method: 'PATCH',
                body: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': this.state.token
                },
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {

                    localStorage["appState"] = JSON.stringify({
                        isLoggedIn: true,
                        userData: data.userData
                    });

                    window.location.href = '/profile/' + data.userData.id + '/bookings/' + data.bookingData.id

                });
        } else {
            this.validator.showMessages();
            // rerender to show messages for the first time
            // you can use the autoForceUpdate option to do this automatically`
            this.forceUpdate();
        }
    }


    render() {
        return (
            <form method="post" onSubmit={this.bookingFormSubmit}>

                <input type="hidden" name="_token" value={this.state.token}/>

                <div className="col-lg-12" style={{ marginTop: '30px' }}>
                    <h2 className="underline">Booking Details</h2>

                    <div className="booking-details-block">

                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="email">Email address:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="email" className="form-control" name="email" id="email" onChange={this.handleUserInput} value={this.state.email}
                                        />
                                    </div>
                                </div>

                                {this.validator.message('email', this.state.email, 'required|email')}

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="title">Title:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <div className="select-wrapper">
                                            <select name="title" id="title" className="form-control" onChange={this.handleUserInput} value={this.state.title} >
                                                <option value="Mr"> Mr </option>
                                                <option value="Mrs"> Mrs </option>
                                                <option value="Miss"> Miss </option>
                                                <option value="Ms"> Ms </option>
                                                <option value="Dr"> Dr </option>
                                                <option value="other"> Other </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="first_name">First name:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="first_name" id="first_name" onChange={this.handleUserInput} value={this.state.first_name} />
                                    </div>
                                </div>

                                {this.validator.message('first_name', this.state.first_name, 'required')}

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="last_name">Last name:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="last_name" id="last_name" onChange={this.handleUserInput} value={this.state.last_name} />
                                    </div>
                                </div>

                                {this.validator.message('last_name', this.state.last_name, 'required')}

                            </div>
                        </div>

                    </div>

                    <div className="booking-details-block">

                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="company">Company</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="company" id="company" onChange={this.handleUserInput} value={this.state.company} />
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="address">Address</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="address" id="address" onChange={this.handleUserInput} value={this.state.address} />
                                    </div>
                                </div>

                                {this.validator.message('address', this.state.address, 'required')}

                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="city">City/Town</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="city" id="city" onChange={this.handleUserInput} value={this.state.city} />
                                    </div>
                                </div>

                                {this.validator.message('city', this.state.city, 'required')}

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="postcode">Postcode</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="postcode" id="postcode" onChange={this.handleUserInput} value={this.state.postcode} />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="region">Region / State</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="region" id="region" onChange={this.handleUserInput} value={this.state.region} />
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="country">Country</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="country" id="country" onChange={this.handleUserInput} value={this.state.country} />
                                    </div>
                                </div>

                                {this.validator.message('country', this.state.country, 'required')}

                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="phone">Phone number</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="phone" id="phone" onChange={this.handleUserInput} value={this.state.phone} />
                                    </div>
                                </div>

                                {this.validator.message('phone', this.state.phone, 'required')}

                            </div>
                        </div>


                    </div>


                </div>

                <div className="col-lg-12">
                    <div className="form-group">
                        <label htmlFor="notes">Additional information or special requirements:</label>
                        <textarea name="notes" id="notes" rows="10" value={this.state.notes} onChange={this.handleUserInput} />
                    </div>
                </div>

                <input type="hidden" name="user_id"/>

                <div className="col-lg-12">
                    <input type="submit" className="btn btn-uppercourt pull-right submit_booking"
                           value="Submit booking"/>
                </div>

            </form>
        );
    }
}
