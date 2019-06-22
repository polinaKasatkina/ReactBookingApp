import React, { Component } from 'react';

export class Register extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            isLoggedIn: false,
            user: {}
        };

        this.submitRegisterForm = this.submitRegisterForm.bind(this);

    }

    submitRegisterForm(e) {

        e.preventDefault();

        const form = e.target;
        const data = new FormData(form);

        fetch('/register', {
            method: 'POST',
            body: data,
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    isLoggedIn: true,
                    user: data
                });

                localStorage["appState"] = JSON.stringify({
                    isLoggedIn: this.state.isLoggedIn,
                    userData: this.state.user
                });

                window.location.href = '/profile';

            });
    }
    

    render() {
        return (
            <div className="card booking-details-block">

                <div className="card-body">
                    <form method="POST" action="/register" aria-label="Register" onSubmit={this.submitRegisterForm}>

                        <input type="hidden" name="_token" value={this.state.token} />

                        <div className="form-group row">
                            <label htmlFor="first_name" className="col-md-4 col-form-label text-md-right">First name</label>

                            <div className="col-md-6">
                                <input id="first_name" type="text" className="form-control" name="first_name" required autoFocus />

                            </div>
                        </div>

                        <div className="form-group row">
                            <label htmlFor="last_name" className="col-md-4 col-form-label text-md-right">Last name</label>

                            <div className="col-md-6">
                                <input id="last_name" type="text" className="form-control" name="last_name" required autoFocus />

                            </div>
                        </div>

                        <div className="form-group row">
                            <label htmlFor="email" className="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                            <div className="col-md-6">
                                <input id="email" type="email" className="form-control" name="email" required />

                            </div>
                        </div>

                        <div className="form-group row">
                            <label htmlFor="password" className="col-md-4 col-form-label text-md-right">Password</label>

                            <div className="col-md-6">
                                <input id="password" type="password" className="form-control" name="password" required />
                            </div>
                        </div>

                        <div className="form-group row">
                            <label htmlFor="password-confirm" className="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div className="col-md-6">
                                <input id="password-confirm" type="password" className="form-control" name="password_confirmation" required />
                            </div>
                        </div>

                        <div className="form-group row mb-0">
                            <div className="col-md-12">
                                <button type="submit" className="btn btn-uppercourt pull-right">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        );
    }

}
