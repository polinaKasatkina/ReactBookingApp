import React, { Component } from 'react';
import SimpleReactValidator from 'simple-react-validator';

export class Settings extends Component {


    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            email: this.props.userData.email,
            title: this.props.userData.title,
            first_name: this.props.userData.first_name,
            last_name: this.props.userData.last_name,
            company: this.props.userData.company,
            address: this.props.userData.address,
            city: this.props.userData.city,
            postcode: this.props.userData.postcode,
            region: this.props.userData.region,
            country: this.props.userData.country,
            phone: this.props.userData.phone,
        };

        this.validator = new SimpleReactValidator();

        this.handleUserInput = this.handleUserInput.bind(this);
        this.formSubmit = this.formSubmit.bind(this);
    }

    handleUserInput(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({
            [name]: value
        });
    }

    formSubmit(e) {

        // TODO move form submit to parent component ??

        e.preventDefault();

        if (this.validator.allValid()) {

            const form = e.target;
            const data = new FormData(form);

            data.append('_method', 'PATCH');

            fetch('/profile/' + this.props.userData.id, {
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

                    localStorage["appState"] = JSON.stringify({
                        isLoggedIn: true,
                        userData: data
                    });

                    this.setState({
                        success_message: 'Information was successfully updated!'
                    });

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
            <form method="patch" onSubmit={this.formSubmit}>

                {this.state.success_message && (
                    <div className="alert alert-dismissible alert-success col-xs-12">
                        <button type="button" className="close" data-dismiss="alert">×</button>
                        <div>
                            <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                            <p> {this.state.success_message} </p>
                        </div>
                    </div>
                )}

                <input type="hidden" name="_token" value={this.state.token} />

                <div className="row">


                    <div className="booking-details-block">

                        <div className="row">
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="email">Email address:</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="email" className="form-control" name="email" id="email" value={this.state.email} onChange={this.handleUserInput} />
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
                                            <select name="title" id="title" className="form-control" value={this.state.title} onChange={this.handleUserInput}  >
                                                <option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Miss">Miss</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Dr">Dr</option>
                                                <option value="other">Other</option>
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
                                        <input type="text" className="form-control" name="first_name" id="first_name"
                                               value={this.state.first_name} onChange={this.handleUserInput}  />
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
                                        <input type="text" className="form-control" name="last_name" id="last_name"
                                               value={this.state.last_name} onChange={this.handleUserInput}  />
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
                                        <input type="text" className="form-control" name="company" id="company"
                                               value={this.state.company} onChange={this.handleUserInput}  />
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="row">
                                    <div className="col-lg-5">
                                        <label htmlFor="address">Address</label>
                                    </div>
                                    <div className="col-lg-7">
                                        <input type="text" className="form-control" name="address" id="address"
                                               value={this.state.address} onChange={this.handleUserInput}  />
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
                                        <input type="text" className="form-control" name="city" id="city" value={this.state.city} onChange={this.handleUserInput}  />
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
                                        <input type="text" className="form-control" name="postcode" id="postcode"
                                               value={this.state.postcode} onChange={this.handleUserInput}  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-lg-4">
                                <label htmlFor="region">Region / State</label>
                                <input type="text" className="form-control" name="region" id="region" value={this.state.region} onChange={this.handleUserInput}  />
                            </div>
                            <div className="col-lg-4">
                                <label htmlFor="country">Country</label>
                                <input type="text" className="form-control" name="country" id="country" value={this.state.country} onChange={this.handleUserInput}  />
                                {this.validator.message('country', this.state.country, 'required')}
                            </div>
                            <div className="col-lg-4">
                                <label htmlFor="phone">Phone number</label>
                                <input type="text" className="form-control" name="phone" id="phone" value={this.state.phone} onChange={this.handleUserInput}  />
                                {this.validator.message('phone', this.state.phone, 'required')}
                            </div>
                        </div>


                    </div>


                    <div className="form-group">
                        <input type="submit" name="commit" value="Save" className="btn btn-uppercourt"/>
                    </div>

                </div>
            </form>

        );
    }
}
