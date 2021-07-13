import React, {Component} from 'react';
import {Text} from 'react-native';
import {Footer, FooterTab, Button, Icon, Container, Content, StyleProvider } from 'native-base';
import getTheme from '../../native-base-theme/components';
import material from '../../native-base-theme/variables/material';

export default class AppFooter extends Component {
    
    render() {
        return (
            
            <StyleProvider style={getTheme(material)}>
            

             
            <Footer >
              
              <FooterTab>
                  <Button >
                      <Icon name="home"/>
                      <Text >Home</Text>
                  </Button>
                  <Button >
                      <Icon name="cube"/>
                      <Text >Project</Text >
                      
                  </Button>
                  <Button >
                      <Icon  name="person"/>
                      <Text >Users</Text >
                  </Button>
                  <Button >
                      <Icon  name="search"/>
                      <Text >Search</Text >
                  </Button>
              </FooterTab>
          </Footer>

            
           
            </StyleProvider>
          
        );
    }
}

module.export = AppFooter;