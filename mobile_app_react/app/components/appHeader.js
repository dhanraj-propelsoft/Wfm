import React, {Component} from 'react';
import {Text,Image} from 'react-native';

import {Header, Left, Button, Icon, Title, Body, Right, StyleProvider} from 'native-base';
import getTheme from '../../native-base-theme/components';
import material from '../../native-base-theme/variables/material';
import { StackNavigator } from "react-navigation";
export default class AppHeader extends Component {
render() {
return (
    <StyleProvider style={getTheme(material)}>
            
           <Header style={{backgroundColor:"#F97C2C"}} >
                   <Left>
                    <Button transparent>
                    <Icon name="md-menu" />
                    </Button>
                    </Left>
                <Body>
            			<Title>Workforce Manager</Title>
                </Body>
                <Right >
                    <Button transparent>
                    
                  
                    <Icon name="md-list" />

                    </Button>
                    </Right>
            </Header>
            </StyleProvider>
);
}
}
module.export = AppHeader;