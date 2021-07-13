import React from "react";
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
  ListItem,
  Text,
  Input,
  Badge,
  Left,
  Right,
  Body,
  Switch,
  Radio,
  Item} from 'native-base';
  import PopupMenu  from './CustomPopup';

//import Menu, { MenuItem, MenuDivider } from 'react-native-material-menu';

import { View, Platform,Modal  } from "react-native";
import {
  MenuProvider ,
  Menu,
  MenuOptions,
  MenuOption,
  MenuTrigger,
  MenuContext
} from 'react-native-popup-menu';


export const SwitchNavigationMenu=(navigation)=>{
  return (
 
  <Menu style={{flex:1}}>
      <MenuTrigger onPress={()=>{handleClick.bind(this)}} >
          <Icon style={{color:'white',fontSize:25,marginRight:5}} name="options"/>
      </MenuTrigger>
    

    
      <MenuOptions optionsContainerStyle={{ marginTop: 40,zIndex:999 }} >
        <MenuOption onSelect={() => alert(`Save`)} text='Save' />
        <MenuOption onSelect={() => alert(`Delete`)} >
          <Text style={{color: 'red'}}>{navigation.state.routeName}</Text>
        </MenuOption>
        <MenuOption onSelect={() => alert(`Not called`)} disabled={true} text='Disabled' />
      </MenuOptions>
    
    </Menu>
  
    
    );
}

export const handleClick=(e)=>{
      if(this.node.contains(e.target)){

          alert("OPEN"); 
          return;
    }
    alert("CLOSE");
}
const CustomHeader = (navigation) => {

  return (
 
  
          <Header  style={{ backgroundColor:'#F97C2C',flexDirection:'row',alignItems:'flex-start'}}>
            <View style={{width:'10%',marginTop:'auto',marginBottom:'auto'}}>
            {(navigation.state.routeName=='JobCardEdit'||navigation.getParam('SearchResult',false)==true)?
                  <Icon name="arrow-back" style={{paddingLeft:10,color:'white'}} size={30}
                     onPress={ () =>(navigation.state.routeName=='JobCardEdit')?navigation.goBack():navigation.push(navigation.state.routeName)}/>:<Icon name="md-menu" style={{paddingLeft:10,color:'white'}} size={30}
                     onPress={ () => navigation.openDrawer()}/>}
                 
            </View>
            <View style={{width:'70%',marginTop:'auto',marginBottom:'auto'}}>

                  <Title>
                   JobCard Manager
                  </Title>
            </View>
            <View style={{width:'25%',marginTop:'auto',marginBottom:'auto',backgroundColor:'green'}}>
            {/* <MenuProvider >
                <MenuContext >
                      {SwitchNavigationMenu(navigation)}
                </MenuContext>
                </MenuProvider> */}
                <PopupMenu />
              </View>
            
          </Header>
          
  );
};

export default CustomHeader;

      {/* <Icon name="arrow-back" style={{paddingLeft:10,color:'white'}} size={30}
                     onPress={ () =>{this._menu.hide();const setParamsAction=navigation.setParams({ isModalVisible: true });navigation.dispatch(setParamsAction);}}/> */}
                {/* <Menu ref={this.setMenuRef}
                    
                    button={<Text onPress={this.showMenu}><Icon style={{color:'white',fontSize:25,marginRight:5}} name="options"/></Text>}
                >
                    <MenuItem onPress={  () => {this._menu.hide(), navigation.navigate('JobCardCreate')}}>Add JobCard</MenuItem>
                    <MenuItem onPress={()=>{this._menu.hide();const setParamsAction=navigation.setParams({ isModalVisible: true });navigation.dispatch(setParamsAction);}}>Search{routeName}</MenuItem>
            
                </Menu> */}