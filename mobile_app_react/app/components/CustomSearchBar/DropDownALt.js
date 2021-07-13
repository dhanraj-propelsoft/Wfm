import PropTypes from 'prop-types';
import React, { Component } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  TextInput,
  Keyboard, TouchableWithoutFeedback
} from 'react-native';
import _ from 'lodash';
import ViewOverflow from 'react-native-view-overflow';

export default class CustomDropDown extends Component {
      static propTypes={
      Data:PropTypes.object,
      style:PropTypes.style,
      onItemSelect:PropTypes.func,
      onChangeText:PropTypes.string,
      defaultValue:PropTypes.number,
      Value:PropTypes.string,
      ModalVisible:PropTypes.string,
    }

   
    constructor(props){
        super(props);
            this.state = {
                searchText:'',
                filteredData: [],
                Value:'',
               
            }
}


ObjectFilter(array, substr) {

  return _.filter(array, _.flow(
  _.identity,
  _.values,
  _.join,
  _.toLower,
  _.partialRight(_.includes, substr)
));

}

  search = (searchText) => {

    this.setState({searchText: ""});
    this.setState({searchText: searchText});
    const regex = new RegExp(`${searchText.trim()}`, 'i');
    const  filteredData= this.props.Data.filter(item => item.name.search(regex) >= 0);
 

  console.log(filteredData);
    this.setState({filteredData: filteredData});

  };

  getData=(item)=>{
    this.setState({searchText:item.name,filteredData:[]});
  //console.log(item);
  }

  dismissKeyboardAction=()=>{
    Keyboard.dismiss;
    this.setState({filteredData:[]});
    console.log("working");
  }


  render() {
    const { Data, style, onItemSelect,onTextChange,defaultValue,Value } = this.props;

    return (

      <TouchableWithoutFeedback onPress={this.dismissKeyboardAction}  
      //style={{ zIndex:999,position:'absolute',top:0,width:'100%' }}
      >
      <View 
      style={{ maxHeight:150, marginTop: 0,zIndex:999,position:'absolute',width:'100%' }}
     
      >
      
        <TextInput onBlur={()=>{this.dismissKeyboardAction}}  ref={input => this.textInput = input}  onChangeText={(text)=>{this.search(text)}} value={this.state.searchText}  style={{width:'90%',backgroundColor:'white',borderColor:'#aaa',borderWidth:1,paddingHorizontal:10,height:40,marginLeft:'auto', borderRadius:10,marginRight:'auto'}}/>
        <FlatList
          contentContainerStyle={{maxheight:200,flexGrow:1,overflow:'scroll'}}
          style={{maxheight:200, width: '90%',marginLeft:'auto',marginRight:'auto', zIndex:999}}
          data={this.state.filteredData && this.state.filteredData.length > 0 ? this.state.filteredData : []}
          
          renderItem={({ item, index }) => {
            return (
        
                
                <TouchableOpacity
                  style={{
                    backgroundColor: '#eff0f1',
                    alignItems: 'center',
                    justifyContent: 'center',
                    padding:10,
                    marginTop:0
                    
                  }}
                  onPress={() => {
                        this.getData(item);
                        this.props.onItemSelect(item.id)
                  }}
                >
                  <Text> {item.name}</Text>
                </TouchableOpacity>
              
            );
          }}
        />
      
      </View>
      </TouchableWithoutFeedback>
    );
  }
}