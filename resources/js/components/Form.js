import React, { Component } from 'react';

import { NumberSpinners } from './NumberSpinners';
import { SearchResults } from './SearchResults';
import Datetime from "react-datetime";

export class Form extends Component {

    constructor(props) {
        super(props);

        this.state = {
            propertiesList: [],
            holidayType: this.props.formData.holiday_type,
            daysOfWeekDisabled: [],
            format: "DD/MM/YYYY",
            inputProps: {placeholder: 'DD/MM/YYYY', name: 'checkin', required: true, disabled: true, id: 'checkin_input'},
            checkoutDate: this.props.formData.checkout,
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            formClass: this.props.proceedBooking ? 'place-booking--booking-block' : '',
            searchBtnName : this.props.proceedBooking ? 'Edit booking' : 'Check availability'
        };

        this.holidayType = this.holidayType.bind(this);
        this.isValid = this.isValid.bind(this);
        this.checkinChange = this.checkinChange.bind(this);
    }

    componentDidMount() {

        fetch('/get_properties', {
            method: 'GET',
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    propertiesList: data
                });

            });
    }

    checkinChange(current) {

        var new_date = Datetime.moment(current, 'DD/MM/YYYY').add(parseInt(this.state.holidayType), 'days');

        this.setState({
            checkoutDate: new_date.format('DD/MM/YYYY')
        });

    }

    isValid(current) {

        var yesterday = Datetime.moment().subtract(1, 'day');

        return current.isAfter(yesterday) && !this.state.daysOfWeekDisabled.includes(current.day());
    }


    holidayType(e) {

        const typeOfHoliday = e.target.value;

        switch (typeOfHoliday) {
            case '3':
            case 3:
                this.setState({daysOfWeekDisabled: [0, 1, 2, 3, 4, 6]});
                break;
            case '4':
            case 4:
                this.setState({daysOfWeekDisabled: [0, 2, 3, 4, 5, 6]});
                break;
            case '7':
            case 7:
                this.setState({daysOfWeekDisabled: [0, 1, 2, 3, 4, 6]});
                break;
            case '14':
            case 14:
                this.setState({daysOfWeekDisabled: [0, 1, 2, 3, 4, 6]});
                break;
        }

        // TODO find solution to update only "disabled" property of object
        this.setState({
            inputProps: {placeholder: 'DD/MM/YYYY', name: 'checkin', required: true, disabled: false, id: 'checkin_input'},
            holidayType: typeOfHoliday
        });

    }


    render() {

        return (
            <div className="col-lg-12">

                {this.props.proceedBooking && (
                    <h2 className="underline edit-booking-heading">Edit booking</h2>
                )}

                <div className={this.state.formClass}>

                    <form id="MagicCarpetSearchBar" action="/search" method="post" onSubmit={this.props.onSubmit}>

                        <input type="hidden" name="_token" value={this.state.token}/>

                        {!this.props.proceedBooking && (
                            <div className="row">
                                <div className="col-lg-3">
                                    <label htmlFor="cottage">Choose cottage</label>
                                </div>
                                <div className="col-lg-9">
                                    <div className="select-wrapper">
                                        <select name="cottage"
                                                style={{ width: '100%', height: '40px', background: '#fff'}}
                                                id="cottage"
                                                className="form-control">
                                            <option value="all">All</option>
                                            {this.state.propertiesList.map((property, i) => {
                                                return <option value={property.id} key={i}> {property.name} </option>;
                                            })}
                                        </select>
                                    </div>

                                </div>
                            </div>
                        )}
                        {this.props.proceedBooking && (
                            <input type="hidden" name="cottage" value="all" />
                        )}

                        <div className="row">
                            <div className="col-lg-3">
                                <label htmlFor="holiday_type">Choose type of holiday break</label>
                            </div>
                            <div className="col-lg-9">
                                <div className="select-wrapper">
                                    <select name="holiday_type"
                                            style={{width: '100%', height: '40px', background: '#fff'}}
                                            id="holiday_type"
                                            className="form-control"
                                            onChange={this.holidayType}
                                            value={this.state.holidayType}>
                                        <option value="">--</option>
                                        <option value="3">Short weekend (Friday-Monday, 3 nights)</option>
                                        <option value="4">Mid week (Monday-Friday, 4 nights)</option>
                                        <option value="7">1 week (Friday-Friday, 7 nights)</option>
                                        <option value="14">2 weeks (Friday-Friday 14 nights)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-6">
                                        <label>Check in</label>
                                    </div>
                                    <div className="col-lg-6">
                                        <div className="row checkin-box">
                                            <Datetime
                                                dateFormat={this.state.format}
                                                input={true} timeFormat={false}
                                                value={this.props.formData.checkin}
                                                isValidDate={ this.isValid }
                                                inputProps={this.state.inputProps}
                                                onChange={this.checkinChange}
                                                closeOnSelect={true}/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-4 col-lg-offset-1">
                                        <label>Check out</label>
                                    </div>
                                    <div className="col-lg-6">
                                        <div className="row checkin-box">
                                            <input type="text" className="form-control"
                                                   id="checkout_input"
                                                   name="checkout" placeholder={this.state.inputProps.placeholder}
                                                   value={this.state.checkoutDate}/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div className="row">
                            <NumberSpinners name="adults" value={this.props.formData.adults} onChange={this.props.onChange} />
                            <NumberSpinners name="children" value={this.props.formData.children} onChange={this.props.onChange} />
                            <NumberSpinners name="infants" value={this.props.formData.infants} onChange={this.props.onChange} />
                        </div>

                        <div className="row">
                            <div className="col-lg-9 col-lg-offset-3">
                                <button type="submit" className="btn btn-uppercourt"> {this.state.searchBtnName} </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        );
    }
}

Form.defaultProps = {
    formData: {
        cottage: 'all',
        holiday_type: 0,
        checkin: "",
        checkout: "",
        adults: 0,
        children: 0,
        infants: 0
    }
};
