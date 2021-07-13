import React, { Component } from 'react';
import {
  StyleSheet,View ,Platform,Image,AsyncStorage  
} from 'react-native';
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
  List,
  ListItem,
  Text,
  Badge,
  Left,
  Right,
  Body,
  Switch,
  Radio,
  Item,
  Picker,
  Separator,Label, Fab} from 'native-base';
import styles from './Navigation_style';

const drawerCover = require("../../../assets/images/drawer-cover.png");
let User="WMS";
let Reg="";
export default class Navigation extends Component {

  SignOut = async()=>{
    console.log("test");
    AsyncStorage.clear();
    this.props.navigation.navigate('AuthLoading');
  }

    render() {
        return (
            <Container>

            <Content  bounces={false} style={{ flex: 1, backgroundColor: "#fff", top: -1 }} >
           
              { <Image source={drawerCover} style={styles.drawerCover} />
                  
     }<Text  style={styles.SidebarTitle}>{User}</Text>
     <Text note style={styles.SidebarSubtitle}>{Reg}</Text>
              <List>
                  <ListItem
                    button
                    noBorder onPress ={() => this.props.navigation.navigate('Dashboard')} >
                    <Left>
                      <Icon name='home'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     WMS home
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                        {/* <Badge
                          style={{
                            borderRadius: 3,
                            height: 25,
                            width: 72,
                          }}
                        >
                          <Text
                            style={styles.badgeText}
                          >sdfsdf</Text>
                        </Badge> */}
                      </Right>
                  </ListItem>
                  <ListItem
                    button
                    noBorder onPress ={() => this.props.navigation.navigate('AddVehicle')} >
                    <Left>
                      <Icon name='car'
                        
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                        Add Vehicle
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                        {/* <Badge
                          style={{
                            borderRadius: 3,
                            height: 25,
                            width: 72,
                          }}
                        >
                          <Text
                            style={styles.badgeText}
                          >sdfsdf</Text>
                        </Badge> */}
                      </Right>
                  </ListItem>
                  
                 <ListItem
                    button
                    noBorder onPress ={() => this.props.navigation.navigate('Search')} >
                    <Left>
                      <Icon name='car'
                        
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                      Search
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                  
                      </Right>
                  </ListItem>

                 {/*   <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='folder'
                        
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     Make
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                     
                      </Right>
                  </ListItem>
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='folder'
                        
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     Modal
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                     
                      </Right>
                  </ListItem>
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='folder'
                        
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     Variant
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                     
                      </Right>
                  </ListItem>
                  <ListItem noBorder>
                      
                  </ListItem>
                 
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='person'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     My Profile
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                      
                      </Right>
                  </ListItem>
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='briefcase'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     Other Packages
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                      
                      </Right>
                  </ListItem>
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='happy'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                     Support
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                      
                      </Right>
                  </ListItem>*/}
                  <ListItem 
                    button
                    noBorder >
                    <Left>
                      <Icon name='egg'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text}>
                      About
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                      
                      </Right>
                  </ListItem>
                 
                  <ListItem
                    button
                    noBorder >
                    <Left>
                      <Icon name='log-out'
                        active
                      
                        style={{ color: "#777", fontSize: 26, width: 30 }}
                      />
                      <Text style={styles.text} onPress={this.SignOut}>
                      Logout
                      </Text>
                    </Left>
                   
                      <Right style={{ flex: 1 }}>
                      
                      </Right>
                  </ListItem>
             </List>

            
            </Content>
          </Container>
        );
    }
}

module.export = Navigation;