import React, { Component } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  TextInput,
  Keyboard, TouchableWithoutFeedback
} from 'react-native';

export default class Demo extends Component {

    constructor(props){
        super(props);
            this.state = {
                data: ['mani','maran','kunar','raja','velan','vetri'],
                searchText:'',
                filteredData: []
            }
}

  search = (searchText) => {
    this.setState({searchText: searchText});
  
    let filteredData = this.state.data.filter(function (item) {
      return item.includes(searchText);
    });
  
    this.setState({filteredData: filteredData});
    console.log(this.state.searchText);

  };

  dismissKeyboardAction=()=>{
    Keyboard.dismiss;
    this.setState({filteredData:[]});
  }

  render() {
    return (
        <TouchableWithoutFeedback onPress={this.dismissKeyboardAction}>
      <View style={{ flex: 1, marginTop: 20 }}>
      
        <TextInput     onChangeText={(value)=>{this.search(value),this.setState({searchText:value})}}  value={this.state.searchText} style={{width:'80%',backgroundColor:'#eff0f1',borderColor:'#aaa',borderWidth:1,paddingHorizontal:10,height:40,marginLeft:'auto',marginRight:'auto'}}/>
        <FlatList
          style={{maxheight:200, position: 'absolute', width: '80%',marginTop: 40,marginLeft:'10%',marginRight:'auto',zIndex:999}}
          data={this.state.filteredData && this.state.filteredData.length > 0 ? this.state.filteredData : (this.state.searchText)?this.state.filteredData:[]}
       
          renderItem={({ item, index }) => {
            return (
        
                
                <TouchableOpacity
                  style={{
                    backgroundColor: '#eff0f1',
                  
                    alignItems: 'center',
                    justifyContent: 'center',
                    padding:10
                  }}
                  onPress={() => {
                        alert(item);
                  }}
                >
                  <Text> {item}</Text>
                </TouchableOpacity>
              
            );
          }}
        />
         <TextInput onChangeText={(value)=>this.search(value)}  value={this.state.searchText} style={{width:250,backgroundColor:'green',paddingHorizontal:10,height:50}}/>
     
      </View>
      </TouchableWithoutFeedback>
    );
  }
}