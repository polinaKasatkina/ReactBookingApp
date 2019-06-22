import React, { Component } from 'react';

export class NumberSpinners extends Component {

    constructor(props) {
        super(props);
        this.state = {
            adultsCount: this.props.value,
            childrenCount: this.props.value,
            infantsCount: this.props.value
        };

        // This binding is necessary to make `this` work in the callback
        this.numberSpinnerClick = this.numberSpinnerClick.bind(this);
    }

    numberSpinnerClick(e) {
        e.preventDefault();

        if (e.currentTarget.dataset.dir == 'up') {

            this.setState({
                [`${e.currentTarget.dataset.input}Count`]: this.state[`${e.currentTarget.dataset.input}Count`] + 1
            })

        } else {

            if (this.state[`${e.currentTarget.dataset.input}Count`] > 0) {
                this.setState({
                    [`${e.currentTarget.dataset.input}Count`]: this.state[`${e.currentTarget.dataset.input}Count`] - 1
                });
            }

        }


    }


    render() {
        return (

            <div className="col-lg-4">
                <div className="row">
                    <div className="col-lg-5">
                        <label>{this.props.name}</label>
                    </div>
                    <div className="col-lg-7 numbers">

                        <div className="row">

                            <div className="input-group number-spinner">
				<span className="input-group-btn data-dwn">
					<button className="btn btn-uppercourt btn-number" data-dir="dwn" data-input={this.props.name}
                            onClick={this.numberSpinnerClick}>
                        <span className="glyphicon glyphicon-minus"></span>
                    </button>
				</span>
                                <input type="text" className="text-center" min="0" max="32"
                                       name={this.props.name} id={this.props.name}
                                       value={this.state[`${this.props.name}Count`]}/>
				<span className="input-group-btn data-up">
					<button className="btn btn-uppercourt btn-number" data-dir="up" data-input={this.props.name}
                            onClick={this.numberSpinnerClick}><span
                        className="glyphicon glyphicon-plus"></span></button>
				</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        )
    }

}
