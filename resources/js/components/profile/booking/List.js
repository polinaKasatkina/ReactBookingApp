import React, { Component } from 'react';

export default class List extends Component {

    constructor(props) {
        super(props);

        let appState = JSON.parse(window.localStorage.getItem('appState'));

        this.state = {
            userData: appState.isLoggedIn ? appState.userData : []
        };

    }

    componentDidMount() {

        fetch('/profile/' + this.state.userData.id + '/get_bookings', {
            method: 'GET',
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    bookings: data
                });

            });

    }

    render() {
        return (
            <table className="table table-responsive table-bookings">
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Properties</th>
                    <th>Dates</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {this.state.bookings && this.state.bookings.map((booking, i) => {

                    let className;
                    let buttonText;

                    switch(booking.status) {
                        case 0:
                            className = 'info';
                            buttonText = 'Waiting for the payment';
                            break;
                        case 1:
                            className = 'warning';
                            buttonText = 'Deposit paid';
                            break;
                        case 2:
                            className = 'success';
                            buttonText = 'Full price paid';
                            break;
                        case 3:
                            className = 'danger';
                            buttonText = 'Cancelled';
                            break;
                        default:
                            className = 'info';
                            buttonText = 'Waiting for the payment';
                            break;
                    }

                    return (
                        <tr key={i}>
                            <td> {booking.id} </td>
                            <td>
                                {booking.properties.map((property, j) => {
                                    return <span key={j}>{ property.name } <br/></span>;
                                })}

                            </td>
                            <td> {booking.start_date} - <br/>{booking.end_date} </td>

                            <td>
                                Total price: &pound;{booking.total_price} <br/>

                            </td>
                            <td>
                                <a href={"/profile/" + this.state.userData.id + "/bookings/" + booking.id}
                                   className="btn btn-default btn-xs"><i className="glyphicon glyphicon-eye-open"></i>
                                    Edit</a>


                                <a href={"/profile/" + this.state.userData.id + "/bookings/" + booking.id}
                                   className={"btn btn-" + className + " btn-xs"}><i
                                    className="glyphicon glyphicon-gbp"></i> { buttonText } </a>
                            </td>
                        </tr>);
                })}

                {!this.state.bookings && (
                    <tr>
                        <td colSpan="4">No bookings added</td>
                    </tr>
                )}

                </tbody>
            </table>
        );
    }
}
