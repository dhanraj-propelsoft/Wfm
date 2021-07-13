import PropTypes from 'prop-types';
import React, { Component } from 'react';
import {StyleSheet,
  View,
 
  FlatList,
  TouchableOpacity,
  TextInput,
  Keyboard, TouchableWithoutFeedback
} from 'react-native';
import { Container,
    Header,
    Title,
    Content,
    Body,
    Button,
    Icon,
    Text,

    Input,
    Left,
    Right,
    Radio,
    List,
    ListItem,
    Item,
    Picker,
    Label,Form,Toast} from 'native-base';
import _ from 'lodash';

import Modal from "react-native-modal";   

import { Dropdown } from 'react-native-material-dropdown';


export default class Confirnation extends Component {
    static propTypes={
        PopupTitle:PropTypes.string,
        ModalVisible:PropTypes.bool,
        PopupEvent:PropTypes.func,
        ConfirmEvent:PropTypes.func,
        ContentText:PropTypes.string,
        ConfirmationBoxText:PropTypes.string,
        CancelBoxText:PropTypes.string
        //style:PropTypes.style,
      }
      _onPress=()=>{
         this.props.PopupEvent();
        
       }
      
      _ConFirmation=()=>{
        
        this.props.ConfirmEvent();
      }
  

      render(){

        const{ModalVisible,PopupTitle,ConfirmationBoxText,CancelBoxText} =this.props;
        
       // console.log(this.props);
     
        return (
            <Modal isVisible={ModalVisible} 
              onRequestClose={() => this._onPress()}
             >
                <View style={[styles.ModalContent]}>
                    <Content >
                            <View style={{flexDirection:'row'}}>
                                    <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto',color:'#757575'}}>{PopupTitle}</Text>
                                    <Button title="Hide modal"   transparent style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}} onPress={() => this._onPress()}>
                                            <Icon name='md-close'></Icon>
                                    </Button>
                            </View>
                            <Form>
                                        {/* <Text note style={{color:'#757575'}}>
                                        {ContentText}
                                        </Text> */}
                                       

                                  <Item style={{borderColor: 'transparent',marginLeft:'auto'}} >

                                        <Button transparent onPress={()=>{this._ConFirmation()}}>
                                          <Text>
                                              {ConfirmationBoxText}
                                          </Text>
                                        </Button>

                                        <Button transparent style={{marginLeft:8}}  onPress={()=>{this._onPress()}}>
                                          <Text>
                                            {CancelBoxText}
                                          </Text>
                                        </Button>

                                </Item>
                            </Form>                                
                     
                    </Content>
                </View>

            </Modal>

        );
      };
    }  

  
    const styles = StyleSheet.create({
        noBorder: {
         borderBottomWidth:0
        },
        colWidth: {
          flex:1
         },
         actionButtonIcon: {
          fontSize: 20,
          height: 22,
          color: 'white',
        },
        Label:{
            color:'#0d0d0dc7',
            fontSize:14
        },
        Title:{
          color:'#0d0d0d',
          marginBottom:3
          
        },
        Label_secondary:{
          color:'#0d0d0dc7',
          paddingTop:10,
          fontSize:14
        },
        InputItem:{
            borderWidth:0,
            borderRadius:10,
            backgroundColor:'white',
           overflow:'hidden'
      
        },
        DisplayImage:{
          width:'50%',
          minHeight:100,
          margin:10,
          alignSelf:'center'
        },
        HiddenImage:{
          width:0,
          minHeight:0,
          marginTop:10,
          alignSelf:'center'
        },
        ImageButton:{
          
          marginBottom:30,
        },
        SelectedImage:{
          borderColor:"#F97C2C",
          borderWidth: 2,
        },
        ErrorInput:{
          color:'red',
          fontSize:13,
          paddingLeft:11
        },
        Input:{
           fontSize:14
        },
        RadioInput:{
          fontSize:14
        },
        Container:{
          backgroundColor:'#f6f6f6',
        },
        ModalContent:{
            backgroundColor: '#ffffff',
            padding: 22,
           justifyContent: 'center',
            alignItems: 'center',
            borderRadius: 4,
            flexDirection:'row',
            borderColor: 'rgba(0, 0, 0, 0.1)' 
            
        },
        FloatingLabel:{
          backgroundColor: '#e8e8e8',
           borderColor: 'transparent',
           borderBottomColor:'#6c6c6c',
           borderTopLeftRadius:2,
           borderTopRightRadius:2
      },
      textInput: {
        alignItems: 'flex-start',
        backgroundColor: '#F0F0F0',
        padding: 12,
        borderRadius: 8,
        marginBottom: 2,
        height:50,
        width:50
      },
      list: {
        borderRadius: 8,
        overflow: 'hidden'
      },
      listItem: {
        alignItems: 'flex-start',
        backgroundColor: '#E0E0E0',
        padding: 12
      },
      intro: {
        backgroundColor: '#444',
        padding: 12,
        borderRadius: 2,
        marginBottom: 12
      },
      introText: {
        color: '#fff'
      },
      DisplayList:{
        display:'flex'
      },
      HideList:{
        display:"none"
      },
      autocompleteContainer:{
        backgroundColor: '#ffffff',
        borderWidth: 0,
        
      },
      
      });
      
      
        