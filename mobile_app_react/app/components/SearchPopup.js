import React, { Component } from 'react';
import {
  StyleSheet,View ,Platform,ScrollView,TouchableOpacity
} from 'react-native';
import { Container,
  Header,
  Title,
  Content,
  Body,
  Button,
  Icon,
  ListItem,
  Text,
  Input,
  Left,
  Right,
  Radio,
  List,
  Item,
  Picker,
  Label,Form,Toast} from 'native-base';

  import Modal from "react-native-modal";   
  import { Dropdown } from 'react-native-material-dropdown';
  import PropTypes from 'prop-types';

export default class SearchPopup extends Component {

  static propTypes ={
  props:PropTypes.array.isRequired,
  SearchStates:PropTypes.array.isRequired,

  }

_findItem(query,JobcardList) {
  if (query === '') {
    return [];
  }
  if(query && isNaN(query) )
  {
    console.log(query);
    const regex = new RegExp(`${query.trim()}`, 'i');
 
    return JobcardList.filter(item => item.name.search(regex) >= 0);
  
  }else{
    return [];
  }
}




render(){

  // console.log("props"+props.routeName);
  
  const {props,SearchStates}= this.props
  
  const SearchItemQuery=SearchStates.Search_status;
  const JobcardList=SearchStates.JobcardStatusList;
  
  const SearchItemList= this._findItem(SearchItemQuery,JobcardList);
  
  //console.log("props2"+route);
  const comp = (a, b) => a.toLowerCase().trim() === b.toLowerCase().trim();

  //console.log("Route "+props.state.routeName);
  
  return (
           <Modal isVisible={props.getParam('isModalVisible')} onRequestClose={() => {const setParamsAction=props.setParams({ isModalVisible: false });props.dispatch(setParamsAction);console.log(props)}}>
              <View style={[styles.ModalContent]}>
                  <Content >
                      <View style={{flexDirection:'row'}}>
                          <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto'}}>Search</Text>
                         
                          <Button title="Hide modal" onPress={()=>{const setParamsAction=props.setParams({ isModalVisible: false });props.dispatch(setParamsAction);props.state.params.ResetSearch()}} transparent style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}}>
                                 <Icon name='md-close' fontSize="35"></Icon>
                          </Button>
                    </View>
      
                    <Form>
                        <Item floatingLabel style={[styles.FloatingLabel]}>
                                <Label> Jobcard No</Label>
                        <Input
                            returnKeyType="next"
                            clearButtonMode="always"
                            autoCapitalize="none"
                            autoCorrect={false}
                            value={SearchStates.jobcard_no}
                            onChangeText={(text) => {
                                             
                              props.state.params.HandleSearchInput("jobcard_no",text);
                                                    }}
                            // onBlur={() => {
                            //         this.setState({
                            //           nameError: validator('Name', this.state.name)
                            //             })
                            //       }}
                            Style={{Color:'#5b5a5a'}} />
                      </Item>
    
                      <Item floatingLabel style={[styles.FloatingLabel,{marginBottom:10}]}>
                        
                        <Label>Customer Name</Label>
                        
                        <Input
                              returnKeyType="next"
                              clearButtonMode="always"
                              autoCapitalize="none"
                              autoCorrect={false}
                              value={SearchStates.customer_name}
                              onChangeText={(text) => {
                                             
                                   props.state.params.HandleSearchInput("customer_name",text);
                                                    }}
                          
                                    
                                    />
                      </Item>
                {(props.state.routeName=='UserList')?null:
                      <Item   style={[styles.FloatingLabel]}>
                      
                            <Dropdown 
                              label='Select Job status' 
                              data={JobcardList} containerStyle={{width:'100%',borderBottomWidth:0}} labelFontSize={14} 
                              onChangeText={(value)=>{props.state.params.HandleSearchInput("JobcardStatus",value)}}
                              value={SearchStates.JobcardStatus}
                              
                             />
                      
                      </Item> 
                } 
                    {/* props.state.params.HandleSearch(); <Text style={[styles.ErrorInput]}> {this.state.searchError  ?this.state.searchError  : null }</Text> */}


                   <Item style={{borderColor: 'transparent',marginTop:10,marginLeft:'auto'}} >

                        <Button onPress={()=>{const setParamsAction=props.setParams({ isModalVisible: false });props.dispatch(setParamsAction);props.state.params.HandleSearch();}}>
                          <Text>
                              Search
                          </Text>
                        </Button>

                        <Button light style={{marginLeft:8}}  onPress={()=>{props.state.params.ResetSearch();}}>
                          <Text>
                            Reset
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
      backgroundColor: 'white',
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


  