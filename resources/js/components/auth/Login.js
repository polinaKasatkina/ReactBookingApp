import React, { Component } from 'react';

export class Login extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            isLoggedIn: false,
            user: []
        };

        this.loginFormSubmit = this.loginFormSubmit.bind(this);

    }

    loginFormSubmit(e) {

        e.preventDefault();

        const form = e.target;
        const data = new FormData(form);


        fetch('/login', {
            method: 'POST',
            body: data,
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                console.log(data)

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
                    <form method="POST" action="/login" aria-label="Login" onSubmit={this.loginFormSubmit}>

                        <input type="hidden" name="_token" value={this.state.token} />

                        <div className="form-group row">
                            <label htmlFor="email" className="col-sm-4 col-form-label text-md-right">E-Mail Address</label>

                            <div className="col-md-6">
                                <input id="email" type="email" className="form-control"
                                       name="email" required autoFocus />
                                    
                            </div>
                        </div>

                        <div className="form-group row">
                            <label htmlFor="password" className="col-md-4 col-form-label text-md-right">Password</label>

                            <div className="col-md-6">
                                <input id="password" type="password"
                                       className="form-control"
                                       name="password" required />
                                    
                            </div>
                        </div>

                        <div className="form-group row">
                            <div className="col-md-6 offset-md-4">
                                <div className="form-check">
                                    <input className="form-check-input" type="checkbox" name="remember"
                                           id="remember" />

                                        <label className="form-check-label" htmlFor="remember">Remember Me</label>
                                </div>
                            </div>
                        </div>

                        <div className="form-group row mb-0">
                            <div className="col-md-12">
                                <button type="submit" className="btn btn-uppercourt pull-right">Login</button>

                                <a className="btn btn-link" href="/password/reset">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        );
    }
    
}
