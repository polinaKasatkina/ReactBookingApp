require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';

import Booking from './components/profile/booking/Booking';

render(
    <Booking />,
    document.getElementById('booking')
);
