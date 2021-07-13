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
import ViewOverflow from 'react-native-view-overflow';

import Modal from "react-native-modal";   


export default class CustomDropDown extends Component {
    static propTypes={
        PopupTitle:PropTypes.string,
        Data:PropTypes.object,
        style:PropTypes.style,
        onItemSelect:PropTypes.func,
        onChangeText:PropTypes.string,
        defaultValue:PropTypes.number,
        Value:PropTypes.string,
        CustomerPopup:PropTypes.func,
        ModalVisible:PropTypes.string,
        PopupEvent:PropTypes.func,
        SelectEvent:PropTypes.func,
        ExtraData:PropTypes.object,
        PeopleType:PropTypes.number,
        DisableButton:PropTypes.bool,
        icon:PropTypes.string
      }
      constructor(props){
        super(props);
            this.state = {
                searchText:'',
                filteredData:[],
                Value:'',
                Data:''
               
            }
           this.setState({
            searchText:'',
            filteredData:[],
            Value:'',
           });
        }







      _onPress=()=>{
        this.props.PopupEvent();
        this.setState({filteredData:[],searchText:''});
      }

      _AddCustomer=()=>{
       this.props.PopupEvent();
       this.props.CustomerPopup();
      }
      _SelectItem=(item)=>{
        this.props.SelectEvent(item);
        this.props.PopupEvent();
        this.setState({filteredData:[],searchText:''});
       
      }

      renderHeader = () => {
        return (
          <Input
          returnKeyType="next"
          clearButtonMode="always"
          autoCapitalize="none"
          autoCorrect={false}
           
            onChangeText={(text) => {
               this.search(text);
                          }}
                          value={this.state.searchText}
            style={{width:'90%',backgroundColor:'white',borderColor:'#aaa',borderWidth:1,paddingHorizontal:10,height:40,marginLeft:'auto', borderRadius:10,marginRight:'auto'}}
            //Style={{Color:'#5b5a5a',borderWidth:1,borderColor:'#5b5a5a'}}
            />
        );
      };
      renderFooter = () => {
        return (
            <View  style={{flex: 0.5,alignItems: 'center'}}>
              

                <Button primary style={{width:'50%',marginLeft:'20%'}} >
                    <Text style={{color:'white',marginLeft:'20%'}}>
                        Add Customer
                    </Text>
                </Button>
            </View>
        );
      };


      renderSeparator = () => {
        return (
          <View
            style={{
              height: 1,
              width: '86%',
              backgroundColor: '#CED0CE',
              marginLeft: '14%',
            }}
          />
        );
      };
      
      search = (searchText) => {

        this.setState({searchText: ""});
        this.setState({searchText: searchText});
        const regex = new RegExp(`${searchText.trim()}`, 'i');
        
        const  filteredData= this.props.Data.filter(item => item.name.search(regex) >= 0);
     
    
        this.setState({filteredData: filteredData});
      //  console.log(this.state.filteredData);
    
      };
      render(){
        const{ModalVisible,PopupEvent,PopupTitle,icon} =this.props;
       // console.log(this.props.Data);
        return (
            <Modal isVisible={ModalVisible} onRequestClose={() => this._onPress()} style={{ backgroundColor:'#f6f6f6'}}>
                <View style={[styles.ModalContent]}>
                <Content >
                      <View style={{flexDirection:'row',minHeight:50}}>
                             <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto'}}>{PopupTitle}</Text>
                                 
                              <TouchableOpacity  onPress={()=>{this._onPress()}}  style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}}>
                                    <Icon name='md-close' fontSize={50} />
                              </TouchableOpacity>
                        </View>
               
                        
                        <List style={{marginTop:0}}>
                            <FlatList
                               // contentContainerStyle={{maxheight:200,flexGrow:1,overflow:'scroll'}}
                              //  style={{maxheight:200, width: '90%',marginLeft:'auto',marginRight:'auto', zIndex:999}}
                                data={(this.state.searchText!=null||this.state.searchText!="" )&& this.state.filteredData && this.state.filteredData > 0 ? this.state.filteredData :(this.state.searchText)?this.state.filteredData:this.props.Data }
                              
                                renderItem={({ item, index }) => {
                                    return (
                                
                                    
                
                                   
                                      <ListItem icon noBorder button={true} onPress={()=>{this._SelectItem(item)}}>
                                            <Left style={{flex:0.2}}>
                                                  {(icon)?
                                                  <Button rounded style={{ backgroundColor: "#FF9501" }}>
                                                        <Icon active name={icon} />
                                                  </Button>:null
                                                }
                                            </Left>
                                            <Body>
                                              <Text style={{fontSize:12}}>{item.name}</Text>
                                            </Body>
                                      </ListItem>
                                        );
                                }}
                                ItemSeparatorComponent={this.renderSeparator} 
                                ListHeaderComponent={this.renderHeader} 
                              //  ListFooterComponent={this.renderFooter}
                                 />
                      </List>
                      {(this.props.DisableButton)?null:
                      (this.state.searchText && this.state.filteredData.length==0)?(
                      <View style={{width:'auto',height:'auto',marginTop:10}}>
                                <Button style={{marginLeft:'auto',marginRight:'auto',paddingLeft:10,paddingRight:10}} onPress={()=>{this._AddCustomer(),this.setState({filteredData:[],searchText:''})}}>
                                  <Text style={{color:'white'}} >
                                    Add Customer
                                  </Text>
                                </Button>
                      </View>
                      ):(null) }
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
            backgroundColor: '#f6f6f6',
            padding: 22,
           // justifyContent: 'center',
            //alignItems: 'center',
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
      
      
        