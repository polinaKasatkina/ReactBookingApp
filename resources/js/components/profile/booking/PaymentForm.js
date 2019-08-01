import React, { Component } from 'react';
import SimpleReactValidator from 'simple-react-validator';
import DateDiff from 'date-diff';
import MaskedInput from 'react-maskedinput'

export class PaymentForm extends Component {

    constructor(props) {
        super(props);

        this.state = {
            token : document.head.querySelector('meta[name="csrf-token"]').content,
            booking: this.props.booking,
            card : this.props.card,
            card_holder_name: this.props.card.name,
            card_number: this.props.card.last4 ? "**** **** **** " + this.props.card.last4 : "",
            card_cvv :  '',
            card_month_year : this.props.card.exp_month ? this.props.card.exp_month + '/' + this.props.card.exp_year : '',
            terms: false
        };

        this.validator = new SimpleReactValidator();

        var date1 = new Date(this.state.booking.start_date); // 2015-12-1
        var date2 = new Date(); // 2014-01-1

        var diff = new DateDiff(date1, date2);
        this.diffDays = diff.days();

        this.handleUserInput = this.handleUserInput.bind(this);
        this.formSubmit = this.formSubmit.bind(this);

    }

    handleUserInput(e) {
        const name = e.target.name;
        const value = name == 'terms' ? e.target.checked : e.target.value;

        this.setState({
            [name]: value
        });
    }

    formSubmit(e) {
        e.preventDefault();

        if (this.validator.allValid()) {

            this.props.paymentSubmit(e);

            //const form = e.target;
            //const data = new FormData(form);
            //
            //fetch('booking/pay/', {
            //    method: 'POST',
            //    body: data,
            //    headers: {
            //        'X-CSRF-TOKEN': this.state.token
            //    },
            //})
            //    .then(response => {
            //        return response.json();
            //    })
            //    .then(data => {
            //
            //        this.setState({
            //            success_message: 'Information was successfully updated!'
            //        });
            //
            //    });

        } else {
            this.validator.showMessages();
            // rerender to show messages for the first time
            // you can use the autoForceUpdate option to do this automatically`
            this.forceUpdate();
        }
    }

    render() {

        return (
            <div className="row" style={{ marginTop: '20px' }}>

                <form method="post" action="{{ url('booking/pay') }}" className="col-lg-12" onSubmit={this.formSubmit}>

                    <input type="hidden" name="_token" value={this.state.token}/>

                    <input type="hidden" name="booking_id" value={this.state.booking.id} />
                    <input type="hidden" name="amount" value={this.state.booking.amount} />

                    <h2 className="underline">Payment method</h2>

                    {this.diffDays >= 14 && (
                        <p>You have {this.state.booking.payment_days} days to make the payment. Please finalise your
                            payment before {this.state.booking.payTillDate}
                            .</p>
                    )}

                    <div className="booking-details-block">

                        <div className="row">
                            <div className="col-lg-5">
                                <label>Card holder name</label>

                                <input name="card_holder_name" className="form-control" type="text"
                                       value={this.state.card_holder_name} onChange={this.handleUserInput} />

                                {this.validator.message('card_holder_name', this.state.card_holder_name, 'required')}

                            </div>
                            <div className="col-lg-4">
                                <label>Card number</label>

                                <MaskedInput mask="1111 1111 1111 1111" name="card_number" size="20" onChange={this.handleUserInput} className="form-control" value={this.state.card_number} />

                                {this.validator.message('card_number', this.state.card_number, 'required')}

                            </div>
                            <div className="col-lg-1">
                                <label>CVV</label>
                                <MaskedInput mask="111" name="card_cvv" onChange={this.handleUserInput} className="form-control" value={this.state.card_cvv} />
                                {this.validator.message('card_cvv', this.state.card_cvv, 'required')}
                            </div>
                            <div className="col-lg-2">
                                <label>Month/Year</label>

                                <MaskedInput mask="11/11" name="card_month_year" placeholder="mm/yy" onChange={this.handleUserInput} className="form-control" value={this.state.card_month_year} />

                                {this.validator.message('card_month_year', this.state.card_month_year, 'required')}
                            </div>
                        </div>


                        <div className="row">
                            <div className="col-lg-12">
                                <ul className="payment-methods">
                                    <li className="visa"></li>
                                    <li className="american-express"></li>
                                    <li className="paypal"></li>
                                    <li className="discover"></li>
                                    <li className="maestro"></li>
                                </ul>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-lg-12">
                                <label>
                                    <input type="checkbox" name="terms" checked={this.state.terms}
                                           onChange={this.handleUserInput} /> Accept <a
                                    href="https://www.uppercourt.co.uk/terms-conditions/"
                                    target="_blank" >Terms & Conditions</a>
                                </label>
                                {this.validator.message('terms', this.state.terms, 'accepted')}
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-lg-12">
                                <button className="btn btn-uppercourt pull-right submit_payment"
                                        id="pay-deposit">
                                    {this.diffDays < 56 && (
                                        <span>Pay full price</span>
                                    )}
                                    {this.diffDays >= 56 && (
                                        <span>Pay deposit</span>
                                    )}
                                </button>
                            </div>
                        </div>


                    </div>


                </form>

            </div>
        );
    }

}
