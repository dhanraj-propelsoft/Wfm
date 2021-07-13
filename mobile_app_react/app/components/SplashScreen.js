/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, {Component} from 'react';
import {Platform, StyleSheet,Image,Dimensions} from 'react-native';
import PropTypes from 'prop-types';
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
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
  var {width} = Dimensions.get('window');
  var {height} = Dimensions.get('window').height;
  //const {routeName } = navigation.state;




class SplashScreen extends Component {
  componentDidMount() {
  setTimeout(()=> {
    this.props.navigation.navigate('AuthLoading')
  },1000); 
}
 

//   setTimeout(
//     ({navigation})=> {
//       this.props.navigation.navigate('Dashboard');
//         },1000
// );


    //   _navigateTo = (routeName: string) => {
    //     const actionToDispatch = NavigationActions.reset({
    //       index: 0,
    //       actions: [NavigationActions.navigate({ routeName })]
    //     })
    //     this.props.navigation.dispatch(actionToDispatch)
    //   }

 render(){
  return (

    <Container style={{backgroundColor:'#F97C2C',flexGrow:1,alignContent:'center', flexDirection:'row',}}>
        <Content >
            <Body>
                            <Image
                style={styles.image }
                source={require('../../assets/images/logo_trans.png')}
                resizeMode="contain" 
                />
                  <Text style={{color:'white',}}>PropelSoft</Text>
            </Body>
            
        </Content>      


  </Container>
  );

}
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
  },
  welcome: {
    fontSize: 20,
    textAlign: 'center',
    margin: 10,
  },
  instructions: {
    textAlign: 'center',
    color: '#333333',
    marginBottom: 5,
  },
  image:{
    width: width * 0.5,
   marginTop:width * 0.14
 },
});


export default SplashScreen;