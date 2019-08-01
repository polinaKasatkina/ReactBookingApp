require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';

import List from './components/profile/booking/List';

render(
    <List />,
    document.getElementById('bookings')
);
