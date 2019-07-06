import React, { Component } from 'react';

import { Tabs, Tab } from 'react-bootstrap';
import { Settings } from './Settings';
import { Password } from './Password';
import { AccountDelete } from './AccountDelete';

export default class Profile extends Component {

    constructor(props) {
        super(props);

        let appState = JSON.parse(window.localStorage.getItem('appState'));

        this.state = {
            userData: appState.isLoggedIn ? appState.userData : []
        };

    }

    render() {
        return (
            <div className="col-lg-10 col-lg-offset-1" style={{ marginTop: "-42px" }}>

                <div className="row">
                    <div className="col-xs-12 std-block userInfo">

                        <Tabs defaultActiveKey={1} id="uncontrolled-tab-example">
                            <Tab eventKey={1} title="Profile info">
                                <Settings userData={this.state.userData} />
                            </Tab>
                            <Tab eventKey={2} title="Password">
                                <Password userData={this.state.userData} />
                            </Tab>
                            <Tab eventKey={3} title="Account">
                                <AccountDelete userData={this.state.userData} />
                            </Tab>
                        </Tabs>

                    </div>
                </div>
            </div>
        );
    }
}
