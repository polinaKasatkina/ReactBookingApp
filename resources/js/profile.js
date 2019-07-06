require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';

import Profile from './components/profile/Profile';

render(
    <Profile />,
    document.getElementById('profile')
);
