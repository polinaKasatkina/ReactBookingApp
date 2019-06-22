import React, { Component } from 'react';

export class PropertyItem extends Component {

    constructor(props) {
        super(props);

        this.state = {
            active: false
        };
        this.toggleClass= this.toggleClass.bind(this);
    }

    toggleClass() {

        const currentState = this.state.active;
        this.setState({ active: !currentState });

        this.props.addToBooking(this.props.property.id);

    };

    render() {

        const { property } = this.props;
        const infants = property.infants > 1 ? 'infants' : 'infant';

        return (
            <li>
                <div className="row">
                    <div className="img-container col-lg-3">
                        <img src={property.img} className="img-responsive"/>
                    </div>
                    <div className="col-lg-6">
                        <p className="property-title pull-left">
                            {property.name}
                        </p>
                        <p className="property-price pull-right">
                            &pound;{property.price}
                        </p>
                        <div className="property-labels">
                            <span className="property-label">{property.bedrooms} bedrooms</span>
                            <span className="property-label">{property.bethrooms} bathrooms</span>
            <span className="property-label">
              Sleeps { property.adults + property.children }
                {property.infants && (
                    <span> and {property.infants} { infants } </span>
                )}

            </span>
                        </div>
                        <div className="property-description">
                            {property.description}
                        </div>

                        {property.url > '' && (<a href={property.url} className="property-see-details">See details</a>)}
                    </div>
                    <div className="col-lg-3">
                        <button className={this.state.active ? 'btn btn-uppercourt add-to-booking checked': "btn btn-uppercourt add-to-booking"} onClick={this.toggleClass}>Add to booking</button>
                    </div>
                </div>

            </li>
        );
    }

}
