import React, { Component } from 'react';

export class BookingForm extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content
        };

    }


    render() {
        return (
            <form method="post">

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
                                        <input type="email" className="form-control" name="email" id="email"
                                        />
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="title">Title:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <div className="select-wrapper">
                                            <select name="title" id="title" className="form-control">
                                                <option
                                                    value="Mr"> Mr
                                                </option>
                                                <option
                                                    value="Mrs"> Mrs
                                                </option>
                                                <option
                                                    value="Miss"> Miss
                                                </option>
                                                <option
                                                    value="Ms"> Ms
                                                </option>
                                                <option
                                                    value="Dr"> Dr
                                                </option>
                                                <option
                                                    value="other"> Other
                                                </option>
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
                                        <input type="text" className="form-control" name="first_name" id="first_name"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="last_name">Last name:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="last_name" id="last_name"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

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
                                        <input type="text" className="form-control" name="company" id="company"/>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="address">Address</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="address" id="address"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="city">City/Town</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="city" id="city"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="postcode">Postcode</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="postcode" id="postcode"/>
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
                                        <input type="text" className="form-control" name="region" id="region"/>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="country">Country</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="country" id="country"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="phone">Phone number</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="phone" id="phone"/>
                                    </div>
                                </div>

                                <p className="text-danger"><i></i></p>

                            </div>
                        </div>


                    </div>


                </div>

                <div className="col-lg-12">
                    <div className="form-group">
                        <label htmlFor="notes">Additional information or special requirements:</label>
                        <textarea name="notes" id="notes" rows="10"></textarea>
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
