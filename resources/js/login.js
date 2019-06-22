require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';

import { Login } from './components/auth/Login';

render(
    <Login />,
    document.getElementById('login')
);
