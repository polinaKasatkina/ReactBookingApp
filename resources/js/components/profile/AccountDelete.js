import React, { Component } from 'react';

export class AccountDelete extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
        };

        this.formSubmit = this.formSubmit.bind(this);
    }

    formSubmit(e) {
        e.preventDefault();


        if (window.confirm('Are you sure you wish to delete your account?')) {

            const form = e.target;
            const data = new FormData(form);

            data.append('_method', 'PATCH');

            fetch('/profile/' + this.props.userData.id + '/edit/account', {
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
                            error_message: data.message
                        });

                    } else {

                        window.location.href = "/login";

                    }

                });
        }


    }

    render() {
        return (
            <div className="row">

                <div className="col-lg-12">
                    <form method="post" action="/profile/id/edit/account" onSubmit={this.formSubmit}>

                        {this.state.error_message && (
                            <div className="alert alert-dismissible alert-danger col-xs-12">
                                <button type="button" className="close" data-dismiss="alert">×</button>
                                <div>
                                    <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                                    <p> {this.state.error_message} </p>
                                </div>
                            </div>
                        )}

                        <input type="hidden" name="_token" value={this.state.token}/>

                        <div className="booking-details-block">

                            <div className="row">
                                <div className="col-lg-5">
                                    <label>Delete account</label>
                                </div>

                                <div className="col-lg-7">
                                    <input type="submit" name="commit" value="Delete"
                                           className="btn btn-lg btn-uppercourt"  />
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        );
    }
}
