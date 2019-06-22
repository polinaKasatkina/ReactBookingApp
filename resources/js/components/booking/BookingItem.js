import React, { Component } from 'react';

export class BookingItem extends Component {

    constructor(props) {
        super(props);

        this.state = {
            properties: [],
            totalPrice: 0
        };

    }

    componentDidMount() {

        fetch('/get_properties_by_id', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                propertiesIds:this.props.properties,
                _token: document.head.querySelector('meta[name="csrf-token"]').content
            })
        })
            .then(response => {
                return response.json();
            })
            .then(data => {

                this.setState({
                    properties: data.properties,
                    totalPrice: data.totalPrice
                });

            });
    }

    render() {
        return (
            <div className="col-lg-12">
                <ul className="place-booking-properties">
                    {this.state.properties.map((property, i) => {
                        return (
                            <li data-property={property.id} key={i}>
                                {property.name}
                                <input type="hidden" name="propertyId[]" value={property.id} />
                            </li>
                        );
                    })}
                </ul>

                <ul className="pull-right place-booking-properties" style={{marginTop: '-10px'}}>
                    <li><strong>Total price:</strong> &pound;{this.state.totalPrice}
                    </li>
                </ul>
            </div>
        );
    }
}
