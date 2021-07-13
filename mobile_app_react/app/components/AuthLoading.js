
import React, {Component} from 'react';
import {Platform, StyleSheet,StatusBar,AsyncStorage, Text, View,Button,ActivityIndicator,TouchableOpacity} from 'react-native';
import { createSwitchNavigator} from 'react-navigation';
//import constant from './constants';

class AuthLoading extends Component {
    constructor(){
        super()
        this.loadApp()
    }
  loadApp = async() =>{
   //AsyncStorage.clear();
  // return false;
  //constant v
   let userToken = await AsyncStorage.getItem('Token');

     
  //   console.warn(this.props.navigation.navigate('App'));
      console.log((userToken?'App':'Auth'));
      this.props.navigation.navigate(userToken?'App':'Auth');
  }

    render() {
                return (
                <View style={styles.container}>
                        <ActivityIndicator/>

                </View>
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
    Input:{
        margin:15,
        height:40,
        padding:5,
        fontSize:16,
        borderBottomWidth:1,
        borderBottomColor:'#428AFB'
    }
  });
  export default AuthLoading
  