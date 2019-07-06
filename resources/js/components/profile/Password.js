import React, { Component } from 'react';
import SimpleReactValidator from 'simple-react-validator';

export class Password extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            current_pass: '',
            new_pass: '',
            confirm_pass: ''
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

        e.preventDefault();

        const form = e.target;
        const data = new FormData(form);

        data.append('_method', 'PATCH');

        if (this.validator.allValid()) {

            fetch('/profile/' + this.props.userData.id + '/edit/password', {
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
                        this.setState({
                            error_message: null,
                            success_message: data.message
                        });
                    }



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

                {this.state.error_message && (
                    <div className="alert alert-dismissible alert-danger col-xs-12">
                        <button type="button" className="close" data-dismiss="alert">×</button>
                        <div>
                            <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                            <p> {this.state.error_message} </p>
                        </div>
                    </div>
                )}

                {this.state.success_message && (
                    <div className="alert alert-dismissible alert-success col-xs-12">
                        <button type="button" className="close" data-dismiss="alert">×</button>
                        <div>
                            <i className="fa fa-info-circle pull-left" aria-hidden="true"></i>
                            <p> {this.state.success_message} </p>
                        </div>
                    </div>
                )}

                <input type="hidden" name="_token" value={this.state.token}/>


                <div className="row">

                    <div className="col-lg-12">

                        <div className="booking-details-block">
                            <div className="row">
                                <div className="col-lg-12">
                                    <div className="row">
                                        <div className="col-lg-5">
                                            <label className="label-light" htmlFor="current_pass">Current
                                                password</label>
                                        </div>
                                        <div className="col-lg-7">
                                            <input className="form-control" required="required" type="password"
                                                   name="current_pass" id="current_pass" onChange={this.handleUserInput} />
                                        </div>
                                    </div>

                                    {this.validator.message('current_pass', this.state.current_pass, 'required')}

                                </div>
                            </div>
                            <div className="row">
                                <div className="col-lg-12">
                                    <div className="row">
                                        <div className="col-lg-5">
                                            <label className="label-light" htmlFor="new_pass">New password</label>
                                        </div>
                                        <div className="col-lg-7">
                                            <input className="form-control" required="required" type="password"
                                                   name="new_pass" id="new_pass" onChange={this.handleUserInput} />
                                        </div>
                                    </div>

                                    {this.validator.message('new_pass', this.state.new_pass, 'required|min:6')}

                                </div>
                            </div>
                            <div className="row">
                                <div className="col-lg-12">
                                    <div className="row">
                                        <div className="col-lg-5">
                                            <label className="label-light" htmlFor="confirm_pass">Repeat
                                                password</label>
                                        </div>
                                        <div className="col-lg-7">
                                            <input className="form-control" required="required" type="password"
                                                   name="confirm_pass" id="confirm_pass" onChange={this.handleUserInput} />
                                        </div>
                                    </div>

                                    {this.validator.message('confirm_pass', this.state.confirm_pass, 'required')}

                                </div>
                            </div>
                        </div>


                        <div className="form-group">
                            <input type="submit" name="commit" value="Update" className="btn btn-uppercourt"/>
                        </div>
                    </div>
                </div>
            </form>
        );
    }
}
