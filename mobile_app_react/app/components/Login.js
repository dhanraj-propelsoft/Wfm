/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, {Component} from 'react';
import {Platform, StyleSheet,AsyncStorage,Alert,View,Image} from 'react-native';
import PropTypes from 'prop-types';
import { Container, Content, Button, Text,H3, Form, Item, Label, Input, Footer, Spinner  } from 'native-base';

import Config from 'react-native-config';



const Logo = require("../../assets/images/logo.png");
const API_URL=Config.API_URL;
class Login extends Component {

  constructor(props){
    super(props)
    this.state = {
        Phone: '',
        inValidPhone: false,
        Password: '',
        errMsgText: '',
        isLoading: false,
    
    };
}



SignIn = async()=>{

    console.log(API_URL);
  const {
    Phone,
    Password,
} = this.state;

if(!Phone || !Password ){
    Alert.alert(
        'Error',
       'Invalid Login creditals',
        [
            {text: 'OK', onPress: () => console.log('OK Pressed')},
        ],
        { cancelable: true }
    )
    return;
}

console.log(`${API_URL}/login`);
fetch(`${API_URL}/login`, {
  method: 'POST',
  headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
  },
  body:JSON.stringify({
      mobile: Phone,
      password: Password,
     
  }),
}).then((response) => response.json())
  .then((responseJson) => {
     console.log(responseJson);
      if(responseJson.status==='1'){
        //console.log( 'Bearer ' +responseJson.token);

        let token = responseJson.token;
        let UserData = responseJson.user;
        let PersonId= responseJson.person_id; 
       console.log(UserData);
        const userToken =   AsyncStorage.setItem('Token',token);
        let Data = AsyncStorage.setItem('UserData',JSON.stringify(UserData));
        let Person_Id = AsyncStorage.setItem('PersonId',PersonId);
    //   userData=this.setState(UserData);
        this.props.navigation.navigate('App');
      //  return false;

      }else{
          this.setState({errMsgText: responseJson.message });
       //   console.warn(this.state.errMsgText);
          Alert.alert(
              'Error',
             'Please enter Correct Credentials',
              [
                  {text: 'OK', onPress: () => console.log('OK Pressed')},
              ],
              { cancelable: true }
          )
          this.props.navigation.navigate('AuthLoading');
      }

  })
  .catch((error) => {
      console.warn('error', error);
      this.setState({regErr: error});
  });

 // await AsyncStorage.setItem('userToken','Mani');
  //console.warn(this.props.navigation.navigate);
 // this.props.navigation.navigate('App');
}

validation = ()=>{
  const {
      inValidPhone,
  } = this.state;
  var phoneVarify = /^\d{10}$/;
  
  if(this.state.Phone != ''){
      phoneVarify.test(this.state.Phone) ? this.setState({inValidPhone: false, inValidPhoneMsg: null}) : this.setState({inValidPhone: true, inValidPhoneMsg: 'Phone no. is invalid'});
  }else{
      this.setState({inValidPhone: true, inValidPhoneMsg: 'Please Enter Your Phone no.'});
  }

  if( !inValidPhone  && this.state.Phone != ''){
    //  this.setState({loading: true});
  //  console.warn(this.SignIn);
      this.SignIn;
  }else{
      Alert.alert(
          'Error',
          'Ph',
          [
            {text: 'OK', onPress: () => console.log('OK Pressed')},
          ],
          { cancelable: true }
      )
  }

}

 render(){
  return (

    <Container style={{backgroundColor:'#F97C2C'}} >
    <Content  contentContainerStyle={{ flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  }} >
        <View style={{backgroundColor:'white',justifyContent:'space-evenly',width:150,height:95,borderRadius:20 }}>
           <Image style={{width:'100%',height:'100%',position:'absolute',top:-10}} source={Logo} />
        </View>
        <View style={{justifyContent:'space-evenly',width:'auto',height:15,marginTop:25 }}>
                <H3  style={{color:'white'}}>
                    Welcome to Propel
                </H3>
        </View>
        <Form>
            <Item  style={style.InputStyle}  floatingLabel>
                <Label style={style.contentText}>Phone</Label>
                <Input keyboardType={'numeric'} maxLength={10} value={this.Phone} onChangeText={value=>this.setState({Phone: value})} style={{color:'white'}}/>
            </Item>
            <Text style={style.errMsg}>{this.inValidPhoneMsg}</Text>
            <Item style={style.InputStyle}  floatingLabel>
                <Label style={style.contentText}>Password</Label>
                <Input  secureTextEntry={true} value={this.Password} onChangeText={value=>this.setState({Password: value})}  style={{color:'white'}}/>
            </Item>
        </Form>  
        {this.state.loading ? <Spinner /> : <View /> }
        <View style={style.btnView} >
            <Button full 
                onPress={this.SignIn}
                style={{backgroundColor:'white'}}
            >   
                <Text style={{color:'#F97C2C',fontWeight:'bold'}}>
                    Login
                </Text>
            </Button>
        </View>
        <View style={style.doubleBtn}>
            {/* <Button transparent onPress={()=>{
                    this.props.navigator.push({
                        screen: 'ForgetPassword',
                        animated: true, 
                        animationType: 'fade',
                        title: 'Forget Password',
                    })
                }}
            >
                <Text>
                    Forgot Password
                </Text>
            </Button> */}
            {/* <Button transparent onPress={()=>{
               
                }}
            >
                <Text>
                    Register
                </Text>
            </Button> */}
        </View>
    </Content>
</Container>
  );

}
}

const style = StyleSheet.create({
  btnView: {
      padding: 5,
      paddingTop: 30,
      minWidth:200,
      justifyContent: 'center',
      alignContent:'center'
  },
  doubleBtn: {
      flexDirection: 'row',
      justifyContent: 'space-between',
  },
  errMsg: {
      paddingLeft: 15,
      fontSize: 12,
      color: 'red'
  },
  contentText:{
      color:'white'
  },
  InputStyle:{
        minWidth:200,
        color:'white'
  }
});


export default Login;