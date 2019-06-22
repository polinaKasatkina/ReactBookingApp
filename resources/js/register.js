require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';

import { Register } from './components/auth/Register';

render(
    <Register />,
    document.getElementById('register')
);
